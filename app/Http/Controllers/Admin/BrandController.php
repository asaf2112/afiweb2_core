<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Marka Listesi
     */
    public function index(Request $request)
    {
        $query = Brand::withCount('products');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('slug', 'LIKE', "%{$search}%")
                  ->orWhere('slogan', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'featured') {
                $query->where('is_featured', true);
            }
        }

        $brands = $query->orderBy('sort_order', 'asc')
                        ->orderBy('name', 'asc')
                        ->paginate(20)
                        ->appends($request->all());

        $totalBrandsCount = Brand::count();
        $activeBrandsCount = Brand::where('is_active', true)->count();
        $featuredBrandsCount = Brand::where('is_featured', true)->count();

        return view('admin.brands.index', compact('brands', 'totalBrandsCount', 'activeBrandsCount', 'featuredBrandsCount'));
    }

    /**
     * Yeni Marka Ekleme Formu
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Yeni Markayı Kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:brands,name',
            'slug' => 'nullable|string|max:100|unique:brands,slug',
            'slogan' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'logo_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'logo_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
        ], [
            'name.required' => 'Marka adı zorunludur.',
            'name.unique' => 'Bu marka adı zaten kayıtlı.',
            'slug.unique' => 'Bu slug bağlantısı zaten kullanımda.',
            'logo_file.max' => 'Logo dosyası en fazla 5MB olabilir.',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        
        // Slug çakışması kontrolü
        $originalSlug = $slug;
        $counter = 1;
        while (Brand::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $logoPath = null;
        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $filename = 'brand_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('brands');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $logoPath = 'brands/' . $filename;
        } elseif (!empty($validated['logo_url'])) {
            $logoPath = trim($validated['logo_url']);
        }

        $brand = Brand::create([
            'name' => trim($validated['name']),
            'slug' => $slug,
            'slogan' => $validated['slogan'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'description' => $validated['description'] ?? null,
            'logo' => $logoPath,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.brands.index')->with('success', "'{$brand->name}' markası başarıyla oluşturuldu.");
    }

    /**
     * Marka Düzenleme Formu
     */
    public function edit(Brand $brand)
    {
        $brand->loadCount('products');
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Marka Bilgilerini Güncelle
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:brands,name,' . $brand->id,
            'slug' => 'nullable|string|max:100|unique:brands,slug,' . $brand->id,
            'slogan' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'logo_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'logo_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
        ], [
            'name.required' => 'Marka adı zorunludur.',
            'name.unique' => 'Bu marka adı zaten kullanımda.',
            'slug.unique' => 'Bu slug bağlantısı zaten kullanımda.',
        ]);

        $oldName = $brand->name;
        $newName = trim($validated['name']);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($newName);

        $logoPath = $brand->logo;
        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $filename = 'brand_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('brands');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $logoPath = 'brands/' . $filename;
        } elseif ($request->filled('logo_url')) {
            $logoPath = trim($request->logo_url);
        } elseif ($request->has('remove_logo')) {
            $logoPath = null;
        }

        $brand->update([
            'name' => $newName,
            'slug' => $slug,
            'slogan' => $validated['slogan'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'description' => $validated['description'] ?? null,
            'logo' => $logoPath,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
        ]);

        // Eğer marka adı değiştiyse ürünlerdeki marka bilgisini de senkronize et
        if ($oldName !== $newName) {
            Product::where('brand', $oldName)->update(['brand' => $newName]);
        }

        return redirect()->route('admin.brands.index')->with('success', "'{$brand->name}' markası başarıyla güncellendi.");
    }

    /**
     * Markayı Sil
     */
    public function destroy(Brand $brand)
    {
        $name = $brand->name;
        $productCount = Product::where('brand', $name)->count();

        // Bağlı ürünlerin marka alanını boşalt
        Product::where('brand', $name)->update(['brand' => null]);

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', "'{$name}' markası silindi. ({$productCount} ürünün markası temizlendi)");
    }

    /**
     * Hızlı Vitrin (Featured) Toggle
     */
    public function toggleFeatured(Brand $brand)
    {
        $brand->is_featured = !$brand->is_featured;
        $brand->save();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_featured' => $brand->is_featured,
                'message' => $brand->is_featured ? 'Marka ana sayfa vitrinine eklendi.' : 'Marka ana sayfa vitrininden kaldırıldı.'
            ]);
        }

        return back()->with('success', 'Vitrin durumu güncellendi.');
    }

    /**
     * Hızlı Aktif/Pasif Toggle
     */
    public function toggleActive(Brand $brand)
    {
        $brand->is_active = !$brand->is_active;
        $brand->save();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $brand->is_active,
                'message' => $brand->is_active ? 'Marka aktifleştirildi.' : 'Marka pasife alındı.'
            ]);
        }

        return back()->with('success', 'Marka durumu güncellendi.');
    }
}