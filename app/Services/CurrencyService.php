<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    /**
     * Get the current USD to TRY exchange rate.
     * Caches the result for 60 minutes.
     *
     * @return float
     */
    public function getUsdTryRate(): float
    {
        return Cache::remember('usd_try_rate', 3600, function () {
            try {
                // TCMB alternative
                // $response = Http::get('https://www.tcmb.gov.tr/kurlar/today.xml');
                // if ($response->successful()) {
                //     $xml = simplexml_load_string($response->body());
                //     foreach ($xml->Currency as $currency) {
                //         if ($currency['CurrencyCode'] == 'USD') {
                //             return (float) $currency->ForexSelling;
                //         }
                //     }
                // }

                // Using open.er-api as primary for reliable JSON
                $response = Http::get('https://open.er-api.com/v6/latest/USD');
                
                if ($response->successful() && $rate = $response->json('rates.TRY')) {
                    Log::info('Fetched new USD/TRY rate: ' . $rate);
                    return (float) $rate;
                }
            } catch (\Exception $e) {
                Log::error('Currency fetch failed: ' . $e->getMessage());
            }

            // Fallback if API fails
            Log::warning('Currency API failed, using fallback rate.');
            return 33.00;
        });
    }

    /**
     * Clear the cached rate to force a new fetch.
     */
    public function clearCache()
    {
        Cache::forget('usd_try_rate');
    }
}
