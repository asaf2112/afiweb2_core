<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\SmsService;

class OrderController extends Controller
{
    /**
     * Sipariş listesi ve filtreleme
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])->latest();

        // 1. Arama (Sipariş Kodu, Müşteri Adı, E-posta, Telefon, Kargo No)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_code', 'LIKE', "%{$search}%")
                  ->orWhere('tracking_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
            });
        }

        // 2. Durum Filtresi
        if ($request->filled('status')) {
            // "Kargoya Verildi" hem "Kargolandı" hem "Kargoya Verildi" uyumlu tutalım
            if ($request->status === 'Kargoya Verildi') {
                $query->whereIn('status', ['Kargoya Verildi', 'Kargolandı']);
            } elseif ($request->status === 'Bekliyor') {
                $query->whereIn('status', ['Bekliyor', 'Beklemede']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // 3. Tarih Filtresi
        if ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }
        if ($request->filled('date_preset')) {
            switch ($request->date_preset) {
                case 'today':
                    $query->whereDate('created_at', now()->today());
                    break;
                case 'this_week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
            }
        }

        $orders = $query->paginate(15)->appends($request->all());

        // Özet İstatistikler (Filtresiz genel sayaçlar)
        $stats = [
            'total'      => Order::count(),
            'bekliyor'   => Order::whereIn('status', ['Bekliyor', 'Beklemede'])->count(),
            'onaylandi'  => Order::where('status', 'Onaylandı')->count(),
            'kargoda'    => Order::whereIn('status', ['Kargoya Verildi', 'Kargolandı'])->count(),
            'tamamlandi' => Order::where('status', 'Tamamlandı')->count(),
            'iptal'      => Order::where('status', 'İptal Edildi')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Sipariş detay sayfası
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Sipariş Durumu ve Kargo Bilgisi Güncelleme
     */
    public function updateStatus(Request $request, $id, SmsService $smsService)
    {
        $request->validate([
            'status'           => 'required|string|in:Bekliyor,Beklemede,Onaylandı,Kargoya Verildi,Kargolandı,Tamamlandı,İptal Edildi',
            'shipping_company' => 'nullable|string|max:100',
            'tracking_number'  => 'nullable|string|max:100',
        ]);

        $order = Order::with('user')->findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->status = $newStatus;

        // Kargo bilgilerini kaydet
        if ($request->has('shipping_company')) {
            $order->shipping_company = $request->shipping_company;
        }
        if ($request->has('tracking_number')) {
            $order->tracking_number = $request->tracking_number;
        }

        // İlk kez kargoya verildiyse tarih ekle
        if (in_array($newStatus, ['Kargoya Verildi', 'Kargolandı']) && !in_array($oldStatus, ['Kargoya Verildi', 'Kargolandı'])) {
            $order->shipped_at = now();
        }

        $order->save();

        // Kargo SMS'i ve E-posta Durum Güncelleme Bildirimi
        $smsSent = false;
        $emailSent = false;

        if (in_array($newStatus, ['Kargoya Verildi', 'Kargolandı']) && !in_array($oldStatus, ['Kargoya Verildi', 'Kargolandı'])) {
            if ($order->user && $order->user->phone) {
                $kargoInfo = '';
                if ($order->shipping_company && $order->tracking_number) {
                    $kargoInfo = " ({$order->shipping_company} Takip No: {$order->tracking_number})";
                }
                $message = "Sayın " . $order->user->name . ", " . ($order->reference_code ?? 'Siparişiniz') . " numaralı siparişiniz kargoya verilmiştir{$kargoInfo}. Afi Bilişim.";
                try {
                    $smsService->sendSms($order->user->phone, $message);
                    $smsSent = true;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning("SMS gönderim hatası: " . $e->getMessage());
                }
            }
        }

        // Müşteriye E-posta Gönderimi
        $customerEmail = $order->user ? $order->user->email : null;
        if (!empty($customerEmail)) {
            try {
                \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\OrderStatusUpdatedMail($order));
                $emailSent = true;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning("Sipariş durumu e-postası hatası: " . $e->getMessage());
            }
        }

        // AJAX isteği ise JSON döndür
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Sipariş durumu ve kargo bilgileri güncellendi.',
                'status' => $order->status,
                'shipping_company' => $order->shipping_company,
                'tracking_number' => $order->tracking_number,
                'tracking_url' => $order->tracking_url,
                'sms_sent' => $smsSent,
                'email_sent' => $emailSent,
            ]);
        }

        $msg = 'Sipariş durumu başarıyla güncellendi.';
        if ($emailSent) {
            $msg .= ' Müşteriye e-posta bildirimi gönderildi.';
        }
        if ($smsSent) {
            $msg .= ' Kargo bildirim SMS\'i iletildi.';
        }

        return redirect()->back()->with('success', $msg);
    }
}
