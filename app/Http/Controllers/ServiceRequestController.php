<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    /**
     * Siteden müşteri teknik servis / destek talebi formu gönderimi
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'              => 'required|string|max:255',
            'phone'             => 'required|string|max:30',
            'email'             => 'nullable|email|max:255',
            'service_type'      => 'nullable|string|max:100',
            'device_model'      => 'nullable|string|max:255',
            'issue_description' => 'nullable|string',
            'description'       => 'nullable|string',
        ]);

        if (auth()->check()) {
            $validatedData['user_id'] = auth()->id();
        }

        if (empty($validatedData['service_type'])) {
            $validatedData['service_type'] = 'Teknik Servis';
        }

        if (empty($validatedData['issue_description']) && !empty($validatedData['description'])) {
            $validatedData['issue_description'] = $validatedData['description'];
        }

        if (empty($validatedData['description']) && !empty($validatedData['issue_description'])) {
            $validatedData['description'] = $validatedData['issue_description'];
        }

        $serviceRequest = new ServiceRequest($validatedData);
        $serviceRequest->status = 'pending';
        $serviceRequest->save();

        return back()->with('success', 'Servis ve destek talebiniz başarıyla alındı. Teknik ekibimiz en kısa sürede sizinle iletişime geçecektir.');
    }

    /**
     * Admin paneli: Destek ve Servis Talepleri Listesi
     */
    public function index(Request $request)
    {
        $query = ServiceRequest::with('user')->latest();

        // 1. Arama (Müşteri Adı, Telefon, E-posta, Cihaz Modeli, Sorun Açıklaması)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('device_model', 'LIKE', "%{$search}%")
                  ->orWhere('service_type', 'LIKE', "%{$search}%")
                  ->orWhere('issue_description', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // 2. Durum Filtresi
        if ($request->filled('status')) {
            if ($request->status === 'Yeni') {
                $query->whereIn('status', ['Yeni', 'pending']);
            } elseif ($request->status === 'İnceleniyor') {
                $query->whereIn('status', ['İnceleniyor', 'in-progress']);
            } elseif ($request->status === 'Çözüldü / Tamamlandı') {
                $query->whereIn('status', ['Çözüldü / Tamamlandı', 'Tamamlandı', 'completed']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // 3. Servis Tipi Filtresi
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        $requests = $query->paginate(15)->appends($request->all());

        // Durum İstatistikleri
        $stats = [
            'total'       => ServiceRequest::count(),
            'new'         => ServiceRequest::whereIn('status', ['Yeni', 'pending'])->count(),
            'in_progress' => ServiceRequest::whereIn('status', ['İnceleniyor', 'in-progress'])->count(),
            'completed'   => ServiceRequest::whereIn('status', ['Çözüldü / Tamamlandı', 'Tamamlandı', 'completed'])->count(),
        ];

        return view('admin.service_requests.index', compact('requests', 'stats'));
    }

    /**
     * Admin: Talep Durumu ve Yönetici Notu Güncelleme
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'     => 'required|string',
            'admin_note' => 'nullable|string',
        ]);

        $serviceRequest = ServiceRequest::findOrFail($id);
        
        $serviceRequest->status = $request->status;
        if ($request->has('admin_note')) {
            $serviceRequest->admin_note = $request->admin_note;
        }
        $serviceRequest->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Talep durumu başarıyla güncellendi.',
                'status'       => $serviceRequest->status,
                'status_label' => $serviceRequest->status_label,
            ]);
        }

        return back()->with('success', 'Talep durumu ve notu başarıyla güncellendi.');
    }

    /**
     * Admin: Talep Silme
     */
    public function destroy($id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        $serviceRequest->delete();

        return back()->with('success', 'Destek/Servis talebi silindi.');
    }

    /**
     * Müşteri cihaz/servis durumunu takip kodu veya ID ile sorgular
     */
    public function track(Request $request)
    {
        $rawCode = trim($request->input('tracking_code', $request->input('code', '')));

        if (empty($rawCode)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lütfen geçerli bir servis takip numarası veya telefon numarası giriniz.'
                ], 422);
            }
            return back()->with('error', 'Lütfen takip numaranızı giriniz.');
        }

        // Temiz sayısal ID çıkarımı (ör. SR-00005 -> 5)
        $cleanId = preg_replace('/[^0-9]/', '', $rawCode);

        $query = ServiceRequest::query();

        if (!empty($cleanId)) {
            $query->where(function($q) use ($rawCode, $cleanId) {
                $q->where('id', (int)$cleanId)
                  ->orWhere('phone', 'LIKE', "%{$rawCode}%");
            });
        } else {
            $query->where('phone', 'LIKE', "%{$rawCode}%");
        }

        $serviceRequest = $query->latest()->first();

        if (!$serviceRequest) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Girdiğiniz takip koduna ('" . htmlspecialchars($rawCode) . "') ait servis kaydı bulunamadı."
                ], 404);
            }
            return back()->with('error', 'Girdiğiniz takip koduna ait servis kaydı bulunamadı.');
        }

        // Aşamaları belirle (1: Kayıt Alındı, 2: İnceleniyor/İşlemde, 3: Tamamlandı, -1: İptal)
        $statusStr = strtolower($serviceRequest->status);
        $stage = 1;
        if (in_array($statusStr, ['inceleniyor', 'in-progress', 'işlemde', 'islemde'])) {
            $stage = 2;
        } elseif (in_array($statusStr, ['çözüldü / tamamlandı', 'tamamlandı', 'completed', 'cozuldu'])) {
            $stage = 3;
        } elseif (in_array($statusStr, ['iptal', 'cancelled', 'iptal edildi'])) {
            $stage = -1;
        }

        $responseData = [
            'success'              => true,
            'tracking_code'        => 'SR-' . str_pad($serviceRequest->id, 5, '0', STR_PAD_LEFT),
            'id'                   => $serviceRequest->id,
            'customer_name'        => \Illuminate\Support\Str::mask($serviceRequest->name, '*', 2, -1),
            'device_model'         => $serviceRequest->device_model ?: 'Cihaz Belirtilmedi',
            'service_type'         => $serviceRequest->service_type ?: 'Teknik Servis',
            'issue_description'    => $serviceRequest->issue_description ?: $serviceRequest->description ?: '-',
            'status'               => $serviceRequest->status,
            'status_label'         => $serviceRequest->status_label,
            'stage'                => $stage,
            'admin_note'           => $serviceRequest->admin_note,
            'created_at_formatted' => $serviceRequest->created_at->format('d.m.Y H:i'),
            'updated_at_formatted' => $serviceRequest->updated_at->format('d.m.Y H:i'),
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($responseData);
        }

        return view('customer.service-tracking-result', compact('serviceRequest', 'responseData'));
    }
}
