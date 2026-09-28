<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * XML Sitemap — Google, Bing ve diğer botlar için dinamik üretilir.
     * Cache: 6 saat (production'da route cache + HTTP header ile destekle)
     */
    public function index(): Response
    {
        $cacheKey     = 'sitemap_xml';
        $cacheDuration = 60 * 6; // 6 saat (dakika)

        $xml = cache()->remember($cacheKey, $cacheDuration, function () {
            return $this->buildSitemapXml();
        });

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=21600');
    }

    private function buildSitemapXml(): string
    {
        $now = now()->toAtomString();

        $urls = [];

        // ── 1. Statik Sayfalar ──────────────────────────────────────────────
        $staticPages = [
            ['loc' => route('home'),              'priority' => '1.0',  'changefreq' => 'daily'],
            ['loc' => route('products.index'),    'priority' => '0.9',  'changefreq' => 'daily'],
            ['loc' => route('second-hand.index'), 'priority' => '0.9',  'changefreq' => 'daily'],
            ['loc' => route('contact'),           'priority' => '0.5',  'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $urls[] = [
                'loc'        => $page['loc'],
                'lastmod'    => $now,
                'changefreq' => $page['changefreq'],
                'priority'   => $page['priority'],
            ];
        }

        // ── 2. Kategoriler ─────────────────────────────────────────────────
        $categories = Category::all();
        foreach ($categories as $cat) {
            $urls[] = [
                'loc'        => route('category.show', $cat->slug),
                'lastmod'    => ($cat->updated_at ?? now())->toAtomString(),
                'changefreq' => 'weekly',
                'priority'   => '0.7',
            ];
        }

        // ── 3. Ürünler ─────────────────────────────────────────────────────
        $products = Product::select(['slug', 'updated_at', 'main_image'])->get();
        foreach ($products as $product) {
            $entry = [
                'loc'        => route('products.show', $product->slug),
                'lastmod'    => ($product->updated_at ?? now())->toAtomString(),
                'changefreq' => 'weekly',
                'priority'   => '0.8',
            ];

            // Ürün görseli varsa image extension ekle
            if (!empty($product->main_image)) {
                $imgUrl = \Illuminate\Support\Str::startsWith($product->main_image, ['http'])
                    ? $product->main_image
                    : asset($product->main_image);
                $entry['image'] = $imgUrl;
            }

            $urls[] = $entry;
        }

        // ── XML üret ───────────────────────────────────────────────────────
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
            $xml .= "    <lastmod>{$url['lastmod']}</lastmod>\n";
            $xml .= "    <changefreq>{$url['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$url['priority']}</priority>\n";

            if (!empty($url['image'])) {
                $xml .= "    <image:image>\n";
                $xml .= "      <image:loc>" . htmlspecialchars($url['image']) . "</image:loc>\n";
                $xml .= "    </image:image>\n";
            }

            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
