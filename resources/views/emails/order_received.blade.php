<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişiniz Alındı - Afi Bilişim</title>
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
                                Donanım & Teknoloji Çözümleri
                            </p>
                        </td>
                    </tr>

                    <!-- Body Hero -->
                    <tr>
                        <td style="padding: 35px 30px 20px 30px;">
                            <div style="background: linear-gradient(135deg, rgba(234, 179, 8, 0.15) 0%, rgba(245, 158, 11, 0.05) 100%); border: 1px solid rgba(234, 179, 8, 0.3); border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 25px;">
                                <h2 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 800; color: #fbbf24;">
                                    🎉 Siparişiniz Başarıyla Alındı!
                                </h2>
                                <p style="margin: 0; font-size: 14px; color: #cbd5e1; line-height: 1.5;">
                                    Bizi tercih ettiğiniz için teşekkür ederiz. Siparişiniz hazırlanmak üzere işleme alınmıştır.
                                </p>
                            </div>

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
                                        Tarih
                                    </td>
                                    <td width="50%" align="right" style="font-size: 13px; color: #cbd5e1; font-weight: 600; padding-top: 10px;">
                                        {{ $order->created_at->format('d.m.Y — H:i') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" style="font-size: 12px; color: #94a3b8; font-weight: 700; text-transform: uppercase; padding-top: 10px;">
                                        Sipariş Durumu
                                    </td>
                                    <td width="50%" align="right" style="font-size: 13px; color: #f59e0b; font-weight: 800; padding-top: 10px;">
                                        {{ $order->status }}
                                    </td>
                                </tr>
                            </table>

                            <!-- Items Table Header -->
                            <h3 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 800; color: #ffffff;">
                                🛒 Sipariş İçeriği
                            </h3>

                            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse; margin-bottom: 25px;">
                                <thead>
                                    <tr style="border-bottom: 1px solid #334155;">
                                        <th align="left" style="padding: 10px; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Ürün</th>
                                        <th align="center" style="padding: 10px; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Adet</th>
                                        <th align="right" style="padding: 10px; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Tutar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr style="border-bottom: 1px solid #1e293b;">
                                            <td style="padding: 12px 10px; font-size: 13px; font-weight: 700; color: #ffffff;">
                                                {{ $item->product_name }}
                                            </td>
                                            <td align="center" style="padding: 12px 10px; font-size: 13px; font-weight: 600; color: #cbd5e1;">
                                                x{{ $item->quantity }}
                                            </td>
                                            <td align="right" style="padding: 12px 10px; font-size: 13px; font-weight: 800; color: #fbbf24;">
                                                {{ number_format($item->price * $item->quantity, 2, ',', '.') }} ₺
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Total -->
                            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #0f172a; border-radius: 10px; padding: 15px;">
                                <tr>
                                    <td style="font-size: 14px; font-weight: 800; color: #ffffff;">
                                        GENEL TOPLAM
                                    </td>
                                    <td align="right" style="font-size: 20px; font-weight: 900; color: #fbbf24;">
                                        {{ number_format($order->total_amount, 2, ',', '.') }} ₺
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin-top: 30px;">
                                <a href="{{ route('orders.show', $order->id) }}" style="display: inline-block; background-color: #eab308; color: #0f172a; font-weight: 900; font-size: 14px; text-decoration: none; padding: 14px 32px; border-radius: 10px; box-shadow: 0 10px 15px -3px rgba(234, 179, 8, 0.3);">
                                    Siparişimi Takip Et →
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
                                Bu e-posta otomatik olarak oluşturulmuştur. Sorularınız için bizimle iletişime geçebilirsiniz.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
