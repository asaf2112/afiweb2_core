<?php

namespace App\Services;

class PaymentService
{
    /**
     * Sanal POS ödeme işlemini gerçekleştirir (Şimdilik Simülasyon)
     *
     * @param array $cardDetails
     * @param float $amount
     * @param array $buyerDetails
     * @return array
     */
    public function processPayment(array $cardDetails, float $amount, array $buyerDetails = [])
    {
        /*
         * TODO: Iyzico, PayTR veya Param gibi gerçek Sanal POS sağlayıcılarının
         * API entegrasyonu (Request/Response) bu metodun içine yazılacak.
         * 
         * Örnek Iyzico Akışı:
         * 1. Options oluştur (API Key, Secret Key)
         * 2. PaymentRequest oluştur (Fiyat, Sepet, Alıcı Bilgileri, Fatura/Teslimat Adresleri)
         * 3. PaymentCard oluştur (Kart no, ay, yıl, cvv)
         * 4. \Iyzipay\Model\Payment::create($request, $options) çağır.
         */

        // Kredi kartı numarasını boşluklardan arındır
        $cardNumber = str_replace(' ', '', $cardDetails['card_number']);

        // Gelişmiş Validasyon Simülasyonu
        if (strlen($cardNumber) !== 16 || !is_numeric($cardNumber)) {
            return [
                'success' => false,
                'message' => 'Kredi kartı numarası 16 haneli olmalıdır.'
            ];
        }

        if (strlen($cardDetails['cvv']) < 3 || strlen($cardDetails['cvv']) > 4 || !is_numeric($cardDetails['cvv'])) {
            return [
                'success' => false,
                'message' => 'Geçersiz CVV.'
            ];
        }

        // Sahte hata senaryosu (Sonu 0000 ile biten kartlar red yesin simülasyonu)
        if (substr($cardNumber, -4) === '0000') {
            return [
                'success' => false,
                'message' => 'Banka reddi: Yetersiz bakiye veya kısıtlı kart.'
            ];
        }

        // Başarılı ödeme simülasyonu
        return [
            'success' => true,
            'message' => 'Ödeme başarıyla tamamlandı.',
            'transaction_id' => 'AFI-TXN-' . strtoupper(uniqid())
        ];
    }
}
