<?php

namespace Tests\Feature\Web;

use App\Support\ToolCatalog;
use Tests\TestCase;

class LocalizedRoutingSeoTest extends TestCase
{
    public function test_legacy_lang_query_redirects_to_locale_path(): void
    {
        $response = $this->get('/?lang=zh');

        $response->assertStatus(301);
        $response->assertRedirect(ToolCatalog::homeUrl('zh'));
    }

    public function test_root_redirects_to_default_locale_home(): void
    {
        $defaultLocale = ToolCatalog::defaultLocale();
        $response = $this->get('/');

        $response->assertStatus(301);
        $response->assertRedirect(ToolCatalog::homeUrl($defaultLocale));
    }

    public function test_localized_home_includes_canonical_and_hreflang_links(): void
    {
        $locale = 'tr';
        $response = $this->get(ToolCatalog::homeUrl($locale));

        $response->assertOk();
        $response->assertSee('rel="canonical"', false);
        $response->assertSee('hreflang="'.$locale.'"', false);
        $response->assertSee('hreflang="x-default"', false);
    }

    public function test_turkish_header_labels_are_translated(): void
    {
        $response = $this->get(ToolCatalog::homeUrl('tr'));

        $response->assertOk();
        $response->assertSee('PDF Birleştir');
        $response->assertSee('PDF Böl');
        $response->assertSee('PDF Sıkıştır');
        $response->assertSee('PDF Dönüştür');
        $response->assertSee('Tüm PDF Araçları');
    }

    public function test_sitemap_xml_contains_localized_home_urls(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertSee(url(ToolCatalog::homeUrl('en')));
        $response->assertSee(url(ToolCatalog::homeUrl('zh')));
    }
}
