<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart', compact('cart', 'total'));
    }

    /**
     * API: Çekmece sepet için sepet verilerini döndürür.
     */
    public function getCartApi()
    {
        return response()->json($this->getCartResponseData());
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = Session::get('cart', []);
        $qtyToAdd = max(1, (int)$request->input('quantity', 1));

        if (isset($cart[$productId])) {
            $newQty = $cart[$productId]['quantity'] + $qtyToAdd;
            if ($product->stock < $newQty) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Stok yetersiz! (Maksimum {$product->stock} adet eklenebilir)"
                    ], 422);
                }
                return redirect()->back()->with('error', 'Yetersiz stok!');
            }
            $cart[$productId]['quantity'] = $newQty;
        } else {
            if ($product->stock < $qtyToAdd) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Ürün stokta kalmamış!'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Ürün stokta kalmamış!');
            }
            $cart[$productId] = [
                'name' => $product->title,
                'slug' => $product->slug,
                'quantity' => $qtyToAdd,
                'price' => $product->final_price,
                'image' => $product->main_image
            ];
        }

        Session::put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            $data = $this->getCartResponseData();
            $data['message'] = "'{$product->title}' sepete eklendi!";
            return response()->json($data);
        }

        return redirect()->back()->with('success', 'Ürün sepete eklendi!');
    }

    public function update(Request $request, $productId)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);
        $cart = Session::get('cart', []);
        $product = Product::find($productId);

        $newQty = (int)$request->quantity;

        if (isset($cart[$productId])) {
            if ($newQty <= 0) {
                unset($cart[$productId]);
            } else {
                if ($product && $product->stock < $newQty) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => "Maksimum stok: {$product->stock} adet"
                        ], 422);
                    }
                    return redirect()->back()->with('error', 'Stok aşımı!');
                }
                $cart[$productId]['quantity'] = $newQty;
            }
            Session::put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($this->getCartResponseData());
        }

        return redirect()->route('cart.index')->with('success', 'Sepet güncellendi.');
    }

    public function remove(Request $request, $productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($this->getCartResponseData());
        }

        return redirect()->route('cart.index')->with('success', 'Ürün sepetten çıkarıldı.');
    }

    public function checkout()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş. Lütfen önce ürün ekleyin.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout', compact('cart', 'total'));
    }

    public function processCheckout(Request $request, PaymentService $paymentService)
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Sepetiniz boş.'], 422);
            }
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş.');
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'card_name'   => 'required|string|max:100',
            'card_number' => 'required|string',
            'card_month'  => 'required|string|size:2',
            'card_year'   => 'required|string|size:2',
            'cvv'         => 'required|string|min:3|max:4',
        ], [
            'card_name.required'   => 'Kart sahibinin adını giriniz.',
            'card_number.required' => 'Kart numarasını giriniz.',
            'card_month.required'  => 'Son kullanma ayını giriniz.',
            'card_year.required'   => 'Son kullanma yılını giriniz.',
            'cvv.required'         => 'CVV kodunu giriniz.'
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $total = collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });

        $cardDetails = [
            'card_name'    => $request->card_name,
            'card_number'  => $request->card_number,
            'expire_month' => $request->card_month,
            'expire_year'  => $request->card_year,
            'cvv'          => $request->cvv
        ];

        $paymentResult = $paymentService->processPayment($cardDetails, $total);

        if (!$paymentResult['success']) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ödeme başarısız: ' . $paymentResult['message']
                ], 422);
            }
            return back()->withInput()->with('error', 'Ödeme başarısız: ' . $paymentResult['message']);
        }

        // Ödeme Başarılı — Sipariş Tablosuna yaz
        $order = Order::create([
            'user_id'        => Auth::id(),
            'reference_code' => $paymentResult['transaction_id'],
            'total_amount'   => $total,
            'status'         => 'Beklemede'
        ]);

        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $productId,
                'product_name' => $item['name'],
                'price'        => $item['price'],
                'quantity'     => $item['quantity']
            ]);

            // Çok Satanlar vitrin istatistiği için satış adedini artırıyoruz
            $prod = Product::find($productId);
            if ($prod) {
                $prod->increment('sales_count', $item['quantity']);
            }
        }

        Session::forget('cart');

        // Otomatik Sipariş Alındı E-postası Gönderimi
        $recipientEmail = Auth::check() ? Auth::user()->email : $request->input('email');
        if (!empty($recipientEmail)) {
            try {
                \Illuminate\Support\Facades\Mail::to($recipientEmail)->send(new \App\Mail\OrderReceivedMail($order));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning("Sipariş e-postası gönderim hatası: " . $e->getMessage());
            }
        }

        $redirectUrl = Auth::check() ? route('orders.show', $order->id) : route('home');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'   => true,
                'reference' => $paymentResult['transaction_id'],
                'redirect'  => $redirectUrl
            ]);
        }

        return redirect()->to($redirectUrl)->with('success', 'Siparişiniz başarıyla alındı! Referans Kodu: ' . $paymentResult['transaction_id']);
    }

    private function getCartResponseData()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        $formattedItems = [];

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            $img = $item['image'] ?? ($product?->main_image ?? null);
            
            $imageUrl = 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=200&q=80';
            if ($img) {
                if (file_exists(public_path($img))) {
                    $imageUrl = asset($img);
                } elseif (file_exists(public_path('storage/' . $img))) {
                    $imageUrl = asset('storage/' . $img);
                } elseif (\Illuminate\Support\Str::startsWith($img, ['http://', 'https://'])) {
                    $imageUrl = $img;
                }
            }

            $price = (float)($item['price'] ?? 0);
            $qty = (int)($item['quantity'] ?? 1);
            $lineTotal = $price * $qty;
            $total += $lineTotal;

            $formattedItems[] = [
                'id' => (int)$id,
                'title' => $item['name'] ?? ($product?->title ?? 'Ürün'),
                'slug' => $item['slug'] ?? ($product?->slug ?? ''),
                'quantity' => $qty,
                'price' => $price,
                'formatted_price' => number_format($price, 2, ',', '.') . ' ₺',
                'line_total' => number_format($lineTotal, 2, ',', '.') . ' ₺',
                'image' => $imageUrl,
                'stock' => $product?->stock ?? 99
            ];
        }

        $cartCount = collect($cart)->sum('quantity');

        return [
            'status' => 'success',
            'cartCount' => $cartCount,
            'total' => $total,
            'formatted_total' => number_format($total, 2, ',', '.') . ' ₺',
            'free_shipping' => $total >= 1500,
            'items' => $formattedItems
        ];
    }
}
