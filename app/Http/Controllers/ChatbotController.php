<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Chatbot asistan sorgularını işler ve akıllı yanıtlar üretir.
     */
    public function query(Request $request)
    {
        $message = trim($request->input('message', ''));
        if (empty($message)) {
            return response()->json([
                'status' => 'error',
                'reply' => 'Lütfen sormak istediğiniz konuyu veya takip kodunuzu yazın.'
            ]);
        }

        $msgLower = mb_strtolower($message, 'UTF-8');

        // 0. Seri Numarası (SKU) Tespiti (Örn: AFI-SRN-00001 veya SRN-00001 veya AFI-SRN-...)
        if (preg_match('/(afi-srn-[a-z0-9-]+|srn-[a-z0-9-]+)/i', $message, $matches)) {
            $srnCode = strtoupper(trim($matches[1]));
            $product = \App\Models\Product::where('serial_number', 'LIKE', "%{$srnCode}%")->first();

            if ($product) {
                $stockBadge = $product->stock > 0 
                    ? "<span style='color:#10b981;font-weight:bold;'>✓ Stokta Var ({$product->stock} adet)</span>" 
                    : "<span style='color:#ef4444;font-weight:bold;'>✕ Stok Tükendi</span>";

                $priceFormatted = number_format($product->final_price, 2, ',', '.') . " ₺";
                $imgUrl = !empty($product->main_image) ? (str_starts_with($product->main_image, 'http') ? $product->main_image : asset($product->main_image)) : asset('images/placeholder.jpg');
                $pTitleSafe = htmlspecialchars($product->title, ENT_QUOTES, 'UTF-8');
                $pSerialSafe = htmlspecialchars($product->serial_number, ENT_QUOTES, 'UTF-8');

                $cartBtnHtml = $product->stock > 0 
                    ? "<button type='button' onclick='addChatProductToCart({$product->id}, \"" . addslashes($pTitleSafe) . "\")' style='background:#eab308;color:#0f172a;font-weight:bold;font-size:10px;padding:4px 10px;border-radius:6px;border:none;cursor:pointer;margin-top:6px;'>🛒 Sepete Ekle</button>" 
                    : "";

                $reply = "🏷️ <b>Seri Numaralı Ürün Bulundu!</b><br><br>"
                       . "<div style='display:flex;gap:10px;align-items:center;background:rgba(255,255,255,0.05);padding:10px;border-radius:12px;border:1px solid rgba(234,179,8,0.3);'>"
                       . "<img src='{$imgUrl}' style='width:55px;height:55px;object-fit:contain;border-radius:8px;background:#fff;padding:3px;flex-shrink:0;'>"
                       . "<div style='flex:1;min-width:0;'>"
                       . "<div style='font-weight:bold;color:#fff;font-size:12px;line-height:1.3;'>{$pTitleSafe}</div>"
                       . "<div style='font-family:monospace;font-size:10px;color:#facc15;margin-top:2px;'>Seri No: {$pSerialSafe}</div>"
                       . "<div style='font-weight:bold;color:#facc15;font-size:13px;margin-top:2px;'>{$priceFormatted}</div>"
                       . "<div style='font-size:11px;'>{$stockBadge}</div>"
                       . $cartBtnHtml
                       . "</div>"
                       . "</div><br>"
                       . "👉 <a href='" . route('products.show', $product->slug) . "' target='_blank' class='text-yellow-400 font-bold underline bg-yellow-500/10 px-2.5 py-1 rounded-lg border border-yellow-500/20 inline-block'>Ürün Detay Sayfasına Git</a>";

                return response()->json(['status' => 'success', 'reply' => $reply, 'type' => 'product']);
            } else {
                $srnCodeSafe = htmlspecialchars($srnCode, ENT_QUOTES, 'UTF-8');
                return response()->json([
                    'status' => 'success',
                    'reply' => "⚠️ <b>{$srnCodeSafe}</b> kodlu seri numarasına ait ürün veritabanında bulunamadı. Lütfen seri numarasını kontrol edip tekrar deneyin."
                ]);
            }
        }

        // Temsilciye Bağlan Modu Tespiti
        if (str_contains($msgLower, 'temsilci') || str_contains($msgLower, 'canlı destek') || str_contains($msgLower, 'müşteri hizmetleri') || str_contains($msgLower, 'temsilciye bağlan')) {
            $reply = "🎧 <b>Afi Canlı Destek Temsilcisi</b><br><br>"
                   . "<div style='background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.3);padding:12px;border-radius:14px;'>"
                   . "<div style='display:flex;align-items:center;gap:8px;margin-bottom:8px;'>"
                   . "<span style='width:9px;height:9px;border-radius:50%;background:#10b981;display:inline-block;' class='animate-pulse'></span>"
                   . "<span style='font-weight:bold;color:#10b981;font-size:12px;'>Müşteri Temsilcimiz Çevrimiçi</span>"
                   . "</div>"
                   . "<p style='font-size:11px;color:#cbd5e1;margin-bottom:10px;line-height:1.4;'>Özel indirim talepleri, sipariş detayları ve teknik destek için 7/24 WhatsApp canlı hattımıza hemen bağlanabilir veya arayabilirsiniz.</p>"
                   . "<div style='display:flex;flex-direction:column;gap:6px;'>"
                   . "<a href='https://wa.me/905555555555?text=" . urlencode("Merhaba, Afi Bilişim canlı destek temsilcisine bağlanmak istiyorum.") . "' target='_blank' style='background:#10b981;color:#fff;font-weight:bold;padding:8px 12px;border-radius:8px;text-align:center;text-decoration:none;font-size:11px;display:flex;align-items:center;justify-content:center;gap:6px;'>"
                   . "💬 WhatsApp Temsilcisine Bağlan (7/24)</a>"
                   . "<a href='tel:+905555555555' style='background:rgba(255,255,255,0.08);color:#facc15;font-weight:bold;padding:7px 12px;border-radius:8px;text-align:center;text-decoration:none;font-size:11px;border:1px solid rgba(234,179,8,0.3);'>"
                   . "📞 0555 555 55 55 (Direkt Çağrı)</a>"
                   . "</div>"
                   . "</div>";

            return response()->json(['status' => 'success', 'reply' => $reply, 'type' => 'agent']);
        }

        // 1. Servis Takip Kodu Tespiti (Örn: SR-123456 veya SR-...)
        if (preg_match('/sr-[a-z0-9]+/i', $message, $matches)) {
            $code = strtoupper($matches[0]);
            $sr = ServiceRequest::where('tracking_code', $code)->first();

            if ($sr) {
                $statusColor = match($sr->status) {
                    'Yeni', 'pending' => 'Tamir İşleminde / İncelemede',
                    'Onarımda', 'processing' => 'Teknik Servis Masasında (Onarılıyor)',
                    'Tamamlandı', 'completed' => 'Onarım Tamamlandı! Teslimat Bekleniyor',
                    'Teslim Edildi' => 'Müşteriye Teslim Edildi',
                    'İptal Edildi' => 'İşlem İptal Edildi',
                    default => $sr->status
                };

                $srCodeSafe = htmlspecialchars($sr->tracking_code, ENT_QUOTES, 'UTF-8');
                $deviceModelSafe = htmlspecialchars($sr->device_model ?: 'Belirtilmedi', ENT_QUOTES, 'UTF-8');
                $statusColorSafe = htmlspecialchars($statusColor, ENT_QUOTES, 'UTF-8');

                $reply = "🔍 <b>Servis Talebiniz Bulundu!</b><br>"
                       . "• <b>Takip Kodu:</b> {$srCodeSafe}<br>"
                       . "• <b>Cihaz:</b> {$deviceModelSafe}<br>"
                       . "• <b>Durum:</b> <span style='color:#eab308;font-weight:bold;'>{$statusColorSafe}</span><br>";
                
                if ($sr->estimated_cost) {
                    $reply .= "• <b>Tahmini Tutar:</b> " . number_format($sr->estimated_cost, 2, ',', '.') . " ₺<br>";
                }
                if ($sr->admin_notes) {
                    $adminNotesSafe = htmlspecialchars($sr->admin_notes, ENT_QUOTES, 'UTF-8');
                    $reply .= "• <b>Tekniker Notu:</b> {$adminNotesSafe}<br>";
                }
                $reply .= "<br>Detaylı bilgi için <a href='/teknik-servis/takip?tracking_code=" . urlencode($sr->tracking_code) . "' class='text-yellow-400 underline font-bold' target='_blank'>buraya tıklayarak</a> takip edebilirsiniz.";

                return response()->json(['status' => 'success', 'reply' => $reply, 'type' => 'service']);
            } else {
                $codeSafe = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
                return response()->json([
                    'status' => 'success',
                    'reply' => "⚠️ <b>{$codeSafe}</b> kodlu servis kaydı bulunamadı. Lütfen takip kodunuzu kontrol edip tekrar deneyin veya <a href='/teknik-servis' class='text-yellow-400 underline'>yeni servis talebi açın</a>."
                ]);
            }
        }

        // 2. Sipariş Kodu veya Numarası Tespiti (Örn: AFI-TXN-123456, AFI-1234, #1234, veya AFI-TXN-... metinleri)
        $order = null;
        $orderQueryCode = null;

        // A) AFI-TXN-... veya AFI-... veya TXN-... formatı tespiti (Özgün işlem referansı)
        if (preg_match('/(afi-txn-[a-z0-9-]+|afi-[a-z0-9-]+|txn-[a-z0-9-]+)/i', $message, $matches)) {
            $orderQueryCode = strtoupper(trim($matches[1]));
            $orderQuery = Order::with('items.product')->where('reference_code', 'LIKE', "%{$orderQueryCode}%");
            if (auth()->check()) {
                $userOrder = (clone $orderQuery)->where('user_id', auth()->id())->first();
                $order = $userOrder ?: $orderQuery->first();
            } else {
                $order = $orderQuery->first();
            }
        }
        // B) #1234 veya "sipariş no 123" veya sayısal/kodsal ID tespiti
        elseif (preg_match('/(?:sipariş\s*(?:no|kodu)?\s*|\#)\s*([a-z0-9-]+)/i', $message, $matches)) {
            $orderQueryCode = strtoupper(trim($matches[1]));
            if (auth()->check()) {
                // Giriş yapmış kullanıcı kendi siparişini ID veya kod ile sorgulayabilir (IDOR koruması)
                $order = Order::with('items.product')
                              ->where('user_id', auth()->id())
                              ->where(function($q) use ($orderQueryCode) {
                                  $q->where('reference_code', 'LIKE', "%{$orderQueryCode}%")
                                    ->orWhere('id', $orderQueryCode);
                              })
                              ->first();
            } else {
                // Misafir kullanıcılar için IDOR koruması: Sadece karmaşık referans koduyla eşleşme yapılır (1-2 basamaklı sayısal ID ile başkasının siparişi ifşa edilmez)
                if (strlen($orderQueryCode) >= 6 && !ctype_digit($orderQueryCode)) {
                    $order = Order::with('items.product')
                                  ->where('reference_code', 'LIKE', "%{$orderQueryCode}%")
                                  ->first();
                }
            }
        }
        // C) Eğer kullanıcı mesajında "sipariş" geçiyorsa ve mesaj içinde kod benzeri kelime varsa
        elseif (str_contains($msgLower, 'sipariş')) {
            preg_match('/[a-z0-9-]{6,}/i', $message, $matches);
            if (!empty($matches[0])) {
                $candidate = strtoupper(trim($matches[0]));
                if (!in_array($candidate, ['SİPARİŞ', 'SIPARIS', 'TAKİBİ', 'TAKIBI', 'NEDİR', 'NEDIR'])) {
                    $q = Order::with('items.product')->where('reference_code', 'LIKE', "%{$candidate}%");
                    if (auth()->check()) {
                        $q->where('user_id', auth()->id());
                    }
                    $found = $q->first();
                    if ($found) {
                        $order = $found;
                        $orderQueryCode = $candidate;
                    }
                }
            }
        }

        // Sipariş Bulundu İse
        if ($order) {
            $statusLabel = match(strtolower($order->status)) {
                'pending', 'beklemede' => '<span style="color:#f59e0b;font-weight:bold;">⏳ Beklemede / İşleme Alındı</span>',
                'processing', 'hazırlanıyor' => '<span style="color:#3b82f6;font-weight:bold;">⚙️ Hazırlanıyor / Paketlemede</span>',
                'shipped', 'kargoda' => '<span style="color:#10b981;font-weight:bold;">🚚 Kargoda / Teslimat Yolunda</span>',
                'completed', 'delivered', 'teslim edildi' => '<span style="color:#10b981;font-weight:bold;">✅ Teslim Edildi</span>',
                'cancelled', 'iptal edildi' => '<span style="color:#ef4444;font-weight:bold;">❌ İptal Edildi</span>',
                default => "<span style='color:#eab308;font-weight:bold;'>" . htmlspecialchars($order->status, ENT_QUOTES, 'UTF-8') . "</span>"
            };

            $refDisplay = $order->reference_code ? $order->reference_code : ('#' . $order->id);
            $refDisplaySafe = htmlspecialchars($refDisplay, ENT_QUOTES, 'UTF-8');

            $reply = "📦 <b>Sipariş Bilgisi Bulundu!</b><br>"
                   . "• <b>Sipariş Kodu:</b> <code style='background:rgba(234,179,8,0.15);color:#facc15;padding:2px 6px;border-radius:4px;'>{$refDisplaySafe}</code><br>"
                   . "• <b>Durum:</b> {$statusLabel}<br>"
                   . "• <b>Toplam Tutar:</b> " . number_format($order->total_amount, 2, ',', '.') . " ₺<br>"
                   . "• <b>Sipariş Tarihi:</b> " . ($order->created_at ? $order->created_at->format('d.m.Y H:i') : 'Bilinmiyor') . "<br>";

            if (!empty($order->shipping_company)) {
                $shipCompanySafe = htmlspecialchars($order->shipping_company, ENT_QUOTES, 'UTF-8');
                $reply .= "• <b>Kargo Firması:</b> {$shipCompanySafe}<br>";
            }
            if (!empty($order->tracking_number)) {
                $trackingNumSafe = htmlspecialchars($order->tracking_number, ENT_QUOTES, 'UTF-8');
                $reply .= "• <b>Kargo Takip No:</b> <code>{$trackingNumSafe}</code><br>";
                if ($order->tracking_url) {
                    $reply .= "👉 <a href='" . e($order->tracking_url) . "' target='_blank' class='text-yellow-400 font-bold underline'>Kargo Takibi Yap (Tıklayın)</a><br>";
                }
            }

            if ($order->items && $order->items->count() > 0) {
                $reply .= "<br><b>Sipariş İçeriği ({$order->items->count()} Ürün):</b><br>";
                foreach ($order->items->take(3) as $item) {
                    $pTitle = $item->product->title ?? ($item->product_name ?? 'Ürün');
                    $pTitleSafe = htmlspecialchars(Str::limit($pTitle, 35), ENT_QUOTES, 'UTF-8');
                    $qtySafe = (int) $item->quantity;
                    $reply .= "• {$pTitleSafe} (x{$qtySafe})<br>";
                }
            }

            $reply .= "<br>Siparişinizi profilinizdeki <a href='/siparislerim' class='text-yellow-400 font-bold underline'>Siparişlerim</a> sayfasından da detaylı inceleyebilirsiniz.";

            return response()->json(['status' => 'success', 'reply' => $reply, 'type' => 'order']);
        } elseif ($orderQueryCode) {
            $orderQueryCodeSafe = htmlspecialchars($orderQueryCode, ENT_QUOTES, 'UTF-8');
            return response()->json([
                'status' => 'success',
                'reply' => "⚠️ <b>{$orderQueryCodeSafe}</b> kodlu sipariş veritabanımızda bulunamadı.<br>Lütfen sipariş kodunuzu kontrol edin veya <a href='/siparislerim' class='text-yellow-400 underline font-bold'>Siparişlerim</a> sayfasından kodunuzu doğrulayın."
            ]);
        }

        // 3. Ürün Önerileri & Bütçe Sorguları (Örn: '50 bin TL altı kasalar', 'mekanik klavye', '20.000 TL altı laptop', '50000 TL', 'rtx 4060')
        $maxPrice = null;
        if (preg_match('/(\d+(?:[\.,]\d+)?)\s*(bin|k)?\s*(?:tl|₺|lira)?\s*(?:altı|altında|kadar|bütçe)/iu', $msgLower, $matches)) {
            $rawNum = (float) str_replace(['.', ','], ['', '.'], $matches[1]);
            $unit = strtolower($matches[2] ?? '');
            if ($unit === 'bin' || $unit === 'k') {
                $maxPrice = $rawNum * 1000;
            } else {
                $maxPrice = $rawNum;
            }
        } elseif (preg_match('/(\d{4,6})\s*(?:tl|₺|lira)?/iu', $msgLower, $matches)) {
            $maxPrice = (float) $matches[1];
        }

        // Özgün Ürün / Kategori Anahtar Kelimelerini Tespit Et (Öncelikli Eşleşme)
        $explicitProductTerms = [];
        $termMap = [
            'klavye' => ['klavye', 'keyboard'],
            'mouse' => ['mouse', 'fare'],
            'kulaklık' => ['kulaklık', 'headset', 'headphone'],
            'monitör' => ['monitör', 'monitor', 'ekran'],
            'laptop' => ['laptop', 'dizüstü', 'notebook'],
            'kasa' => ['kasa', 'masaüstü', 'desktop'],
            'ram' => ['ram', 'bellek', 'ddr4', 'ddr5'],
            'ssd' => ['ssd', 'nvme', 'm.2', 'disk', 'storage'],
            'ekran kartı' => ['ekran kartı', 'gpu', 'rtx', 'gtx', 'radeon'],
            'işlemci' => ['işlemci', 'cpu', 'ryzen', 'intel', 'i7', 'i5', 'i9'],
            'anakart' => ['anakart', 'motherboard'],
            'güç kaynağı' => ['güç kaynağı', 'psu', 'power supply'],
            'oyuncu' => ['oyuncu', 'gaming']
        ];

        $matchedCategoryLabels = [];
        foreach ($termMap as $label => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($msgLower, $kw)) {
                    $explicitProductTerms[] = $kw;
                    if (!in_array($label, $matchedCategoryLabels)) {
                        $matchedCategoryLabels[] = mb_convert_case($label, MB_CASE_TITLE, 'UTF-8');
                    }
                }
            }
        }

        $isGeneralProductQuery = $maxPrice !== null 
            || !empty($explicitProductTerms)
            || str_contains($msgLower, 'öner') || str_contains($msgLower, 'tavsiye') || str_contains($msgLower, 'popüler') 
            || str_contains($msgLower, 'ürün') || str_contains($msgLower, 'fiyat') || str_contains($msgLower, 'stok');

        if ($isGeneralProductQuery) {
            $query = \App\Models\Product::with('category')->where('stock', '>', 0);

            // Bütçe filtresi
            if ($maxPrice !== null && $maxPrice > 0) {
                $query->where(function($q) use ($maxPrice) {
                    $q->where(function($sub) use ($maxPrice) {
                        $sub->whereNotNull('discount_price')->where('discount_price', '<=', $maxPrice);
                    })->orWhere(function($sub) use ($maxPrice) {
                        $sub->whereNull('discount_price')->where('price', '<=', $maxPrice);
                    });
                });
            }

            // 1. ÖNCELİK: Kullanıcının Aradığı Spesifik Ürün / Kategori Filtresi
            if (!empty($explicitProductTerms)) {
                $query->where(function($q) use ($explicitProductTerms) {
                    foreach ($explicitProductTerms as $term) {
                        $q->orWhere('title', 'LIKE', "%{$term}%")
                          ->orWhere('badge', 'LIKE', "%{$term}%")
                          ->orWhere('usage_status', 'LIKE', "%{$term}%")
                          ->orWhereHas('category', function($catQ) use ($term) {
                              $catQ->where('name', 'LIKE', "%{$term}%");
                          });
                    }
                });
            }

            // Marka / Donanım Modeli Arama (Örn: Asus, MSI, Corsair, Lenovo)
            preg_match_all('/(asus|msi|corsair|lenovo|hp|dell|logitech|razer|kingston|samsung|thermaltake)/i', $message, $brandMatches);
            if (!empty($brandMatches[0])) {
                foreach ($brandMatches[0] as $brand) {
                    $query->where('title', 'LIKE', "%{$brand}%");
                }
            }

            $products = $query->inRandomOrder()->take(3)->get();

            // Eşleşen ürün bulundu ise göster
            if ($products->count() > 0) {
                $categoryTitle = !empty($matchedCategoryLabels) ? implode(' / ', $matchedCategoryLabels) : 'Ürün';
                $categoryTitleSafe = htmlspecialchars($categoryTitle, ENT_QUOTES, 'UTF-8');
                $headerText = $maxPrice 
                    ? "💰 <b>" . number_format($maxPrice, 0, ',', '.') . " ₺ Bütçenize Uygun {$categoryTitleSafe} Önerileri:</b><br><br>" 
                    : "🔥 <b>Sizin İçin Seçtiğimiz {$categoryTitleSafe} Modelleri:</b><br><br>";

                $reply = $headerText;
                foreach ($products as $p) {
                    $img = !empty($p->main_image) ? (str_starts_with($p->main_image, 'http') ? $p->main_image : asset($p->main_image)) : asset('images/placeholder.jpg');
                    $priceStr = number_format($p->final_price, 2, ',', '.') . " ₺";
                    $pUrl = route('products.show', $p->slug);

                    $pTitleSafe = htmlspecialchars($p->title, ENT_QUOTES, 'UTF-8');
                    $pSerialSafe = htmlspecialchars($p->serial_number, ENT_QUOTES, 'UTF-8');
                    $cartBtnHtml = $p->stock > 0 
                        ? "<button type='button' onclick='addChatProductToCart({$p->id}, \"" . addslashes($pTitleSafe) . "\")' style='background:#eab308;color:#0f172a;font-weight:bold;font-size:10px;padding:3px 8px;border-radius:6px;border:none;cursor:pointer;margin-top:4px;'>🛒 Sepete Ekle</button>" 
                        : "<span style='font-size:9px;color:#ef4444;'>Stokta Yok</span>";

                    $reply .= "<div style='display:flex;gap:10px;align-items:center;background:rgba(255,255,255,0.04);padding:8px 10px;border-radius:12px;border:1px solid rgba(234,179,8,0.25);margin-bottom:8px;'>"
                           . "<img src='{$img}' style='width:48px;height:48px;object-fit:contain;border-radius:6px;background:#fff;padding:2px;flex-shrink:0;'>"
                           . "<div style='flex:1;min-width:0;'>"
                           . "<a href='{$pUrl}' target='_blank' style='font-weight:bold;color:#fff;font-size:11px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;'>{$pTitleSafe}</a>"
                           . "<div style='font-family:monospace;font-size:9px;color:#facc15;'>SN: {$pSerialSafe}</div>"
                           . "<div style='display:flex;align-items:center;justify-content:space-between;margin-top:2px;'>"
                           . "<span style='font-weight:bold;color:#facc15;font-size:12px;'>{$priceStr}</span>"
                           . $cartBtnHtml
                           . "</div>"
                           . "</div>"
                           . "</div>";
                }
                $reply .= "<br>Tüm ürün kataloğumuzu <a href='/urunler' class='text-yellow-400 font-bold underline'>Ürünler Sayfamızdan</a> inceleyebilirsiniz.";

                return response()->json(['status' => 'success', 'reply' => $reply, 'type' => 'recommendation']);
            } 
            // Kullanıcı belirli bir ürün kategorisi aradı ve veritabanında bulunamadıysa (Alakasız ürün önerme!)
            else if (!empty($matchedCategoryLabels)) {
                $categoryTitle = implode(' / ', $matchedCategoryLabels);
                $categoryTitleSafe = htmlspecialchars($categoryTitle, ENT_QUOTES, 'UTF-8');
                $budgetText = $maxPrice ? " " . number_format($maxPrice, 0, ',', '.') . " ₺ bütçenize uygun" : "";
                
                return response()->json([
                    'status' => 'success',
                    'reply' => "⚠️ <b>'{$categoryTitleSafe}'</b> kategorisinde{$budgetText} ürün veritabanımızda bulunamadı.<br><br>Lütfen farklı bir kategori arayabilir veya <a href='/urunler' class='text-yellow-400 font-bold underline'>Tüm Ürünler</a> sayfamızdan arama yapabilirsiniz."
                ]);
            }
            // Sadece bütçe girilip hiç ürün bulunamadıysa
            else if ($maxPrice) {
                return response()->json([
                    'status' => 'success',
                    'reply' => "⚠️ <b>" . number_format($maxPrice, 0, ',', '.') . " ₺</b> bütçe altında şu an stokta uygun ürün bulunamadı. Lütfen bütçenizi arttırarak veya <a href='/urunler' class='text-yellow-400 font-bold underline'>Ürünler</a> sayfamızdan arama yaparak tekrar deneyin."
                ]);
            }
        }

        // 4. Kargo & Teslimat
        if (str_contains($msgLower, 'kargo') || str_contains($msgLower, 'teslimat') || str_contains($msgLower, 'ne zaman gelir')) {
            $reply = "🚚 <b>Kargo ve Teslimat Bilgisi:</b><br>"
                   . "• Hafta içi saat <b>16:00'ya kadar</b> verilen tüm siparişler aynı gün kargoya teslim edilir!<br>"
                   . "• Anlaşmalı kargo firmalarımızla (Yurtiçi & Aras Kargo) 1-3 iş günü içerisinde adresinize ulaştırılır.<br>"
                   . "• 1.500 ₺ ve üzeri alışverişlerinizde <b>kargo ücretsizdir</b>.";
            return response()->json(['status' => 'success', 'reply' => $reply]);
        }

        // 5. İkinci El & Garanti
        if (str_contains($msgLower, 'ikinci el') || str_contains($msgLower, '2.el') || str_contains($msgLower, 'garanti') || str_contains($msgLower, 'test')) {
            $reply = "🛡️ <b>Afi Bilişim İkinci El Güvencesi:</b><br>"
                   . "• Tüm ikinci el masaüstü, laptop ve parçalarımız <b>FurMark, MemTest ve OCCT stress testlerinden</b> 100% başarıyla geçmiştir.<br>"
                   . "• Ürünlerimiz <b>6 Ay Afi Bilişim Birebir Değişim Garanti</b> belgesiyle gönderilir.";
            return response()->json(['status' => 'success', 'reply' => $reply]);
        }

        // 6. PC Toplama & Sistem Tavsiyesi
        if (str_contains($msgLower, 'pc toplama') || str_contains($msgLower, 'sistem') || str_contains($msgLower, 'tavsiye') || str_contains($msgLower, 'oyun bilgisayarı')) {
            $reply = "⚡ <b>Donanım & PC Toplama Asistanı:</b><br>"
                   . "İhtiyacınıza ve bütçenize en uygun parçaları otomatik uyumluluk kontrolü yaparak toplamak için harika bir aracımız var!<br><br>"
                   . "👉 <a href='/pc-toplama' class='text-yellow-400 font-bold underline bg-yellow-500/10 px-2 py-1 rounded'>PC Toplama Sihirbazı'nı Başlat</a>";
            return response()->json(['status' => 'success', 'reply' => $reply]);
        }

        // 7. Ödeme & Taksit
        if (str_contains($msgLower, 'ödeme') || str_contains($msgLower, 'taksit') || str_contains($msgLower, 'kredi kartı') || str_contains($msgLower, 'havale')) {
            $reply = "💳 <b>Ödeme & Taksit İmkanları:</b><br>"
                   . "• Tüm Kredi Kartlarına <b>12 Ay'a varan taksit</b> imkanı.<br>"
                   . "• 3D Secure 256-bit SSL güvenli ödeme altyapısı.<br>"
                   . "• Havale / EFT ile ödemelerde <b>%3 Ekstra İndirim</b> uygulanmaktadır.";
            return response()->json(['status' => 'success', 'reply' => $reply]);
        }

        // 8. Canlı Destek Temsilcisi / İletişim
        if (str_contains($msgLower, 'canlı destek') || str_contains($msgLower, 'temsilci') || str_contains($msgLower, 'müşteri hizmetleri') || str_contains($msgLower, 'telefon') || str_contains($msgLower, 'iletişim')) {
            $reply = "🎧 <b>Müşteri Temsilcimize Ulaşın:</b><br>"
                   . "• <b>Telefon:</b> <a href='tel:+905555555555' class='text-yellow-400 font-bold'>0555 555 55 55</a><br>"
                   . "• <b>WhatsApp 7/24:</b> <a href='https://wa.me/905555555555' target='_blank' class='text-emerald-400 font-bold underline'>WhatsApp ile Bağlan</a><br>"
                   . "• <b>E-Posta:</b> info@afibilisim.com";
            return response()->json(['status' => 'success', 'reply' => $reply]);
        }

        // Varsayılan / Fallback Yanıt
        $defaultReply = "🤖 Merhaba! Ben <b>Afi Asistan</b>.<br>"
                      . "Size nasıl yardımcı olabilirim? Aşağıdaki butonlardan seçebilir veya aradığınızı yazabilirsiniz:<br><br>"
                      . "• <b>Ürün Önerileri:</b> 'Ürün Öner' veya aradığınız modeli yazın.<br>"
                      . "• <b>Seri No Sorgulama:</b> Ürün seri kodunu yazın (Örn: <code>AFI-SRN-00001</code>)<br>"
                      . "• <b>Sipariş Takibi:</b> Sipariş kodunuzu yazın (Örn: <code>AFI-TXN-123456</code>)";

        return response()->json(['status' => 'success', 'reply' => $defaultReply]);

        return response()->json(['status' => 'success', 'reply' => $defaultReply]);
    }
}
