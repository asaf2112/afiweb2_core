<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function getCategorySpecs(Category $category)
    {
        // Eğer veritabanında spec_fields (JSON) doluysa onu kullan
        if ($category->spec_fields && is_array($category->spec_fields)) {
            
            // Eğer JSON zaten [{name: 'Kapasite', type: 'text'}] formatında bir array ise doğrudan döndür
            if (isset($category->spec_fields[0]) && is_array($category->spec_fields[0]) && isset($category->spec_fields[0]['name'])) {
                return response()->json($category->spec_fields);
            }

            // Eğer JSON {"Panel Tipi": "text"} gibi key-value formatındaysa çevir
            $formattedSpecs = [];
            foreach ($category->spec_fields as $name => $type) {
                $formattedSpecs[] = [
                    'name' => $name,
                    'type' => $type
                ];
            }
            return response()->json($formattedSpecs);
        }

        // Eğer json boşsa, eski many-to-many SpecType ilişkisine dön (geriye dönük uyumluluk)
        return response()->json($category->specTypes()->distinct()->get());
    }

    public function index(Request $request)
    {
        $query = Product::with('category');

        // Arama Filtresi (Kelime bazlı: başlık, slug, ID)
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('slug', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('serial_number', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('id', $searchTerm);
            });
        }

        // Kategori Filtresi (Ana veya alt kategori)
        if ($request->filled('category_id')) {
            $catId = $request->category_id;
            $cat = Category::with('children')->find($catId);
            if ($cat && $cat->children->isNotEmpty()) {
                $catIds = array_merge([$cat->id], $cat->children->pluck('id')->toArray());
                $query->whereIn('category_id', $catIds);
            } else {
                $query->where('category_id', $catId);
            }
        }

        // Ürün Durum Filtresi (Sıfır / İkinci El)
        if ($request->filled('condition_type')) {
            $query->where('condition_type', $request->condition_type);
        }

        // Stok Durum Filtresi
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'critical':
                    $query->where('stock', '>', 0)->where('stock', '<=', 2);
                    break;
                case 'out_of_stock':
                    $query->where('stock', 0);
                    break;
                case 'low':
                    $query->where('stock', '<=', 5);
                    break;
                case 'in_stock':
                    $query->where('stock', '>', 0);
                    break;
            }
        }

        // Sıralama (Sort)
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'stock_asc':
                    $query->orderBy('stock', 'asc');
                    break;
                case 'stock_desc':
                    $query->orderBy('stock', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('id', 'asc');
                    break;
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'latest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        // Genel Stok İstatistikleri
        $stockStats = [
            'total'        => Product::count(),
            'critical'     => Product::where('stock', '>', 0)->where('stock', '<=', 2)->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'in_stock'     => Product::where('stock', '>', 0)->count(),
        ];

        // Filtreleme için tüm kategoriler (Ana ve Alt kategorileri ile)
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('name')->get();

        $products = $query->paginate(15)->appends($request->all());
        return view('admin.products.index', compact('products', 'stockStats', 'categories'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if ($request->has('discount_expires_at') && !$request->filled('discount_end_date')) {
            $request->merge(['discount_end_date' => $request->input('discount_expires_at')]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:100|unique:products,serial_number',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id',
            'condition_type' => 'required|in:new,used',
            'usage_status' => 'nullable|string|in:Full Set,Sadece Kasa,Sadece Monitör',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric|min:0',
            'discount_end_date' => 'nullable|date',
            'discount_expires_at' => 'nullable|date',
            'stock' => 'required|integer',
            'badge' => 'nullable|string|max:255',
            'is_bestseller' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',

            'description' => 'nullable|string',
            'specs' => 'nullable|array',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $validated['is_bestseller'] = $request->has('is_bestseller');
        $validated['is_featured'] = $request->has('is_featured');

        $price = (float) $validated['price'];
        $discountPrice = !empty($validated['discount_price']) ? (float)$validated['discount_price'] : null;

        if ($discountPrice === null || $discountPrice <= 0 || $discountPrice >= $price) {
            $validated['discount_price'] = null;
            $validated['discount_end_date'] = null;
        } else {
            $validated['discount_price'] = $discountPrice;
            $rawDate = $request->input('discount_end_date') ?: $request->input('discount_expires_at');
            if (empty($rawDate)) {
                $validated['discount_end_date'] = now()->addDays(7);
            } else {
                $validated['discount_end_date'] = \Carbon\Carbon::parse($rawDate);
            }
        }
        unset($validated['discount_expires_at']);

        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        
        // Eğer alt kategori seçildiyse asıl kategori_id odur
        if (!empty($validated['sub_category_id'])) {
            $validated['category_id'] = $validated['sub_category_id'];
        }
        unset($validated['sub_category_id']);

        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('products'), $filename);
            $validated['main_image'] = 'products/' . $filename;
        }

        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                $product->images()->create([
                    'image_url' => $path,
                    'is_main' => false
                ]);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ürün başarıyla eklendi.',
                'product' => $product
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Ürün başarıyla eklendi.');
    }

    public function edit(Product $product)
    {
        $categories = Category::whereNull('parent_id')->get();
        
        // Ürünün mevcut kategorisinin bir üst kategorisi var mı kontrol et
        $mainCategoryId = $product->category && $product->category->parent_id 
                            ? $product->category->parent_id 
                            : $product->category_id;
                            
        $subCategoryId = $product->category && $product->category->parent_id 
                            ? $product->category_id 
                            : null;

        return view('admin.products.edit', compact('product', 'categories', 'mainCategoryId', 'subCategoryId'));
    }

    public function update(Request $request, Product $product)
    {
        if ($request->has('discount_expires_at') && !$request->filled('discount_end_date')) {
            $request->merge(['discount_end_date' => $request->input('discount_expires_at')]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:100|unique:products,serial_number,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id',
            'condition_type' => 'required|in:new,used',
            'usage_status' => 'nullable|string|in:Full Set,Sadece Kasa,Sadece Monitör',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric|min:0',
            'discount_end_date' => 'nullable|date',
            'discount_expires_at' => 'nullable|date',
            'stock' => 'required|integer',
            'badge' => 'nullable|string|max:255',
            'is_bestseller' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',

            'description' => 'nullable|string',
            'specs' => 'nullable|array',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $validated['is_bestseller'] = $request->has('is_bestseller');
        $validated['is_featured'] = $request->has('is_featured');

        $price = (float) $validated['price'];
        $discountPrice = !empty($validated['discount_price']) ? (float)$validated['discount_price'] : null;

        if ($discountPrice === null || $discountPrice <= 0 || $discountPrice >= $price) {
            $validated['discount_price'] = null;
            $validated['discount_end_date'] = null;
        } else {
            $validated['discount_price'] = $discountPrice;
            $rawDate = $request->input('discount_end_date') ?: $request->input('discount_expires_at');
            if (empty($rawDate)) {
                $validated['discount_end_date'] = now()->addDays(7);
            } else {
                $validated['discount_end_date'] = \Carbon\Carbon::parse($rawDate);
            }
        }
        unset($validated['discount_expires_at']);

        $validated['slug'] = Str::slug($validated['title']) . '-' . $product->id;
        
        // Eğer alt kategori seçildiyse asıl kategori_id odur
        if (!empty($validated['sub_category_id'])) {
            $validated['category_id'] = $validated['sub_category_id'];
        }
        unset($validated['sub_category_id']);

        if ($request->hasFile('main_image')) {
            // Eski resmi sil
            if ($product->main_image && file_exists(public_path($product->main_image))) {
                unlink(public_path($product->main_image));
            }
            
            $file = $request->file('main_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('products'), $filename);
            $validated['main_image'] = 'products/' . $filename;
        }

        $product->fill($validated);
        $product->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                $product->images()->create([
                    'image_url' => $path,
                    'is_main' => false
                ]);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ürün başarıyla güncellendi.',
                'product' => $product->fresh()
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Ürün başarıyla güncellendi.');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image && file_exists(public_path($product->main_image))) {
            unlink(public_path($product->main_image));
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Ürün başarıyla silindi.');
    }

    public function destroyImage($id, Request $request)
    {
        $image = ProductImage::findOrFail($id);

        // Storage::delete() kontrolü
        if (Storage::disk('public')->exists($image->image_url)) {
            Storage::disk('public')->delete($image->image_url);
        }

        // İstenildiği gibi ek fiziksel dosya varlık kontrolü (file_exists)
        $physicalPath = public_path('storage/' . $image->image_url);
        if (file_exists($physicalPath) && is_file($physicalPath)) {
            unlink($physicalPath);
        }

        $image->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Görsel başarıyla silindi.');
    }

    public function destroyMainImage(Product $product, Request $request)
    {
        if ($product->main_image && file_exists(public_path($product->main_image))) {
            unlink(public_path($product->main_image));
        }
        
        $product->main_image = null;
        $product->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Ana görsel başarıyla silindi.');
    }

    public function bulkDiscount(Request $request)
    {
        $validated = $request->validate([
            'target_type' => 'required|in:selected,category',
            'category_id' => 'required_if:target_type,category|nullable|exists:categories,id',
            'product_ids' => 'required_if:target_type,selected|nullable|array',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'discount_end_date' => 'nullable|date'
        ]);

        $query = Product::query();

        if ($validated['target_type'] == 'category') {
            $query->where('category_id', $validated['category_id']);
        } else {
            $query->whereIn('id', $validated['product_ids']);
        }

        $products = $query->get();
        $affectedCount = 0;

        foreach ($products as $product) {
            $oldPrice = $product->price;
            $newPrice = $oldPrice;

            if ($validated['discount_type'] == 'percentage') {
                $newPrice = $oldPrice - ($oldPrice * ($validated['discount_value'] / 100));
            } else {
                $newPrice = $oldPrice - $validated['discount_value'];
            }

            if ($newPrice < 0) $newPrice = 0;

            if ($newPrice > 0 && $newPrice < $oldPrice) {
                // Update discount fields instead of main price
                $product->discount_price = $newPrice;
                $product->discount_end_date = !empty($validated['discount_end_date']) 
                    ? \Carbon\Carbon::parse($validated['discount_end_date']) 
                    : now()->addDays(7);
                $product->save();
                
                $affectedCount++;

                // Fiyat Alarmı Kontrolü
                $alerts = \App\Models\PriceAlert::where('product_id', $product->id)
                    ->where('is_notified', false)
                    ->where('target_price', '>=', $newPrice)
                    ->get();

                foreach ($alerts as $alert) {
                    $contact = $alert->user_id ? ($alert->user->email ?? $alert->user->phone) : $alert->contact_info;
                    \Illuminate\Support\Facades\Log::info("Toplu İndirim Alarmı: [{$product->title}] kampanyalı fiyatı {$newPrice} ₺'ye düştü. Bildirim: {$contact}");
                    $alert->is_notified = true;
                    $alert->save();
                }
            }
        }

        return redirect()->back()->with('success', "İndirim başarıyla uygulandı! {$affectedCount} adet ürünün fiyatı güncellendi ve ilgili fiyat alarmları tetiklendi.");
    }

    /**
     * Ürünün Flash İndirim ve kampanya verilerini tamamen siler / pasife alır.
     */
    public function removeDiscount(Product $product, Request $request)
    {
        $product->discount_price = null;
        $product->discount_end_date = null;
        $product->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "'{$product->title}' ürününün flash indirimi kaldırıldı."
            ]);
        }

        return redirect()->back()->with('success', "'{$product->title}' ürününün flash indirimi kaldırıldı ve ana sayfa vitrininden çıkarıldı.");
    }

    /**
     * Ürünün Çok Satan veya Haftanın Fırsatı etiketini dinamik olarak değiştirir.
     */
    public function toggleFlag(Product $product, Request $request)
    {
        $flag = $request->input('flag');
        if (in_array($flag, ['is_bestseller', 'is_featured'])) {
            $product->$flag = !$product->$flag;
            $product->save();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'flag'    => $flag,
                    'value'   => $product->$flag,
                    'message' => 'Ürün vitrin durumu güncellendi.'
                ]);
            }
            return redirect()->back()->with('success', 'Ürün vitrin etiketi güncellendi.');
        }

        return response()->json(['success' => false, 'message' => 'Geçersiz etiket.'], 400);
    }
}
