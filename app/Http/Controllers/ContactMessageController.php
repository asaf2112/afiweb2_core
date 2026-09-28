<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Müşteri tarafından doldurulan iletişim formunu kaydeder.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        ContactMessage::create($validatedData);

        return back()->with('success', 'Mesajınız başarıyla iletildi. En kısa sürede sizinle iletişime geçeceğiz.');
    }

    /**
     * Admin Paneli: Gelen İletişim Mesajlarını Listeler.
     */
    public function index()
    {
        $messages = ContactMessage::latest()->get();
        return view('admin.contact_messages.index', compact('messages'));
    }

    /**
     * Admin Paneli: İletişim mesajını siler.
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return back()->with('success', 'İletişim mesajı başarıyla silindi.');
    }

    /**
     * Admin Paneli: Mesajın okundu durumunu günceller.
     */
    public function toggleRead($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['is_read' => !$message->is_read]);

        return back()->with('success', 'Mesaj durumu güncellendi.');
    }

    /**
     * Admin Paneli: Tüm okunmamış bildirimleri okundu olarak işaretler (AJAX).
     */
    public function markAllRead()
    {
        ContactMessage::where('is_read', false)->update(['is_read' => true]);
        \App\Models\ServiceRequest::whereIn('status', ['Yeni', 'pending'])->update(['status' => 'İşleme Alındı']);
        \App\Models\Order::where('status', 'Beklemede')->update(['status' => 'Hazırlanıyor']);
        \App\Models\Product::where('stock', '<=', 2)->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Tüm bildirimler okundu olarak işaretlendi.'
        ]);
    }

    /**
     * Admin Paneli: Tekil bir bildirimi okundu olarak işaretler (AJAX).
     */
    public function markSingleRead(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        if ($type === 'contact') {
            ContactMessage::where('id', $id)->update(['is_read' => true]);
        } elseif ($type === 'service') {
            \App\Models\ServiceRequest::where('id', $id)->whereIn('status', ['Yeni', 'pending'])->update(['status' => 'İşleme Alındı']);
        } elseif ($type === 'order') {
            \App\Models\Order::where('id', $id)->where('status', 'Beklemede')->update(['status' => 'Hazırlanıyor']);
        } elseif ($type === 'product') {
            \App\Models\Product::where('id', $id)->update(['is_read' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bildirim okundu olarak işaretlendi.'
        ]);
    }
}
