<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Durumu Güncellendi - Afi Bilişim</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0b0f19; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #e2e8f0;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #0b0f19; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="background-color: #151c2c; border-radius: 16px; border: 1px solid #2d3748; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #0f172a; padding: 30px; text-align: center; border-bottom: 2px solid #eab308;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 900; color: #ffffff; letter-spacing: -0.5px;">
                                AFİ <span style="color: #eab308;">BİLİŞİM</span>
                            </h1>
                            <p style="margin: 5px 0 0 0; font-size: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase; tracking: 1px;">
                                Sipariş Durum Bildirimi
                            </p>
                        </td>
                    </tr>

                    <!-- Body Hero -->
                    <tr>
                        <td style="padding: 35px 30px 20px 30px;">
                            
                            @if(in_array($order->status, ['Kargoya Verildi', 'Kargolandı']))
                                <div style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.15) 0%, rgba(99, 102, 241, 0.05) 100%); border: 1px solid rgba(168, 85, 247, 0.3); border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 25px;">
                                    <h2 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 800; color: #c084fc;">
                                        🚚 Siparişiniz Kargoya Verildi!
                                    </h2>
                                    <p style="margin: 0; font-size: 14px; color: #cbd5e1; line-height: 1.5;">
                                        Siparişiniz paketlenerek kargo firmasına teslim edilmiştir.
                                    </p>
                                </div>
                            @elseif($order->status == 'Tamamlandı')
                                <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.05) 100%); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 25px;">
                                    <h2 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 800; color: #34d399;">
                                        ✅ Siparişiniz Tamamlandı!
                                    </h2>
                                    <p style="margin: 0; font-size: 14px; color: #cbd5e1; line-height: 1.5;">
                                        Siparişiniz teslim edilmiştir. Bizi tercih ettiğiniz için teşekkür ederiz!
                                    </p>
                                </div>
                            @else
                                <div style="background: linear-gradient(135deg, rgba(234, 179, 8, 0.15) 0%, rgba(245, 158, 11, 0.05) 100%); border: 1px solid rgba(234, 179, 8, 0.3); border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 25px;">
                                    <h2 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 800; color: #fbbf24;">
                                        🔔 Siparişinizin Durumu Güncellendi
                                    </h2>
                                    <p style="margin: 0; font-size: 14px; color: #cbd5e1; line-height: 1.5;">
                                        Siparişinizin yeni durumu: <strong>{{ $order->status }}</strong>
                                    </p>
                                </div>
                            @endif

                            <!-- Shipping Details If Available -->
                            @if($order->tracking_number)
                                <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1e1b4b; border: 1px solid #4338ca; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                                    <tr>
                                        <td colspan="2" style="font-size: 14px; font-weight: 800; color: #a5b4fc; padding-bottom: 12px; text-transform: uppercase;">
                                            📦 Kargo Takip Bilgileri
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px; color: #94a3b8; font-weight: 700; padding-bottom: 6px;">Kargo Firması:</td>
                                        <td align="right" style="font-size: 13px; color: #ffffff; font-weight: 800; padding-bottom: 6px;">{{ $order->shipping_company ?? 'Kargo Firması' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px; color: #94a3b8; font-weight: 700; padding-bottom: 12px;">Takip Numarası:</td>
                                        <td align="right" style="font-size: 14px; color: #fbbf24; font-weight: 900; font-family: monospace; padding-bottom: 12px;">{{ $order->tracking_number }}</td>
                                    </tr>
                                    @if($order->tracking_url)
                                        <tr>
                                            <td colspan="2" align="center" style="padding-top: 10px;">
                                                <a href="{{ $order->tracking_url }}" target="_blank" style="display: inline-block; background-color: #6366f1; color: #ffffff; font-weight: 800; font-size: 13px; text-decoration: none; padding: 10px 24px; border-radius: 8px;">
                                                    Kargomu Takip Et →
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            @endif

                            <!-- Order Reference Meta -->
                            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1e293b; border-radius: 10px; padding: 15px; margin-bottom: 25px;">
                                <tr>
                                    <td width="50%" style="font-size: 12px; color: #94a3b8; font-weight: 700; text-transform: uppercase;">
                                        Sipariş Kodu
                                    </td>
                                    <td width="50%" align="right" style="font-size: 14px; color: #ffffff; font-weight: 800; font-family: monospace;">
                                        {{ $order->reference_code ?? ('Sipariş #' . $order->id) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" style="font-size: 12px; color: #94a3b8; font-weight: 700; text-transform: uppercase; padding-top: 10px;">
                                        Güncel Durum
                                    </td>
                                    <td width="50%" align="right" style="font-size: 14px; color: #fbbf24; font-weight: 800; padding-top: 10px;">
                                        {{ $order->status }}
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin-top: 25px;">
                                <a href="{{ route('orders.show', $order->id) }}" style="display: inline-block; background-color: #eab308; color: #0f172a; font-weight: 900; font-size: 14px; text-decoration: none; padding: 14px 32px; border-radius: 10px; box-shadow: 0 10px 15px -3px rgba(234, 179, 8, 0.3);">
                                    Sipariş Detaylarını Görüntüle →
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #0f172a; padding: 25px 30px; text-align: center; border-top: 1px solid #1e293b;">
                            <p style="margin: 0 0 5px 0; font-size: 12px; color: #64748b; font-weight: 600;">
                                Afi Bilişim Hizmetleri & Donanım Ekosistemi
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #475569;">
                                Bu e-posta otomatik olarak oluşturulmuştur.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
