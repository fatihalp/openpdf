# openpdf.com.tr

Kamusal fayda icin gelistirilen acik kaynak PDF donusum platformu.

## Teknoloji

- Laravel `12.x`
- PostgreSQL
- Laravel Horizon (queue)
- FilamentPHP `v5`
- Bootstrap 5 (UI)
- Vue.js 3 (frontend JS kutuphanesi)

## Servisler

- PDF to Word
- PDF to Excel
- PDF to JPG
- Compress PDF
- Merge PDF
- Word to PDF
- Excel to PDF
- JPG to PDF

## Yetkilendirme ve Limitler

- Uyelik zorunlu degil.
- Ziyaretci limiti: islem basina `100 dosya` ve `100 MB`
- Google ile giris yapanlar: limitsiz (spam onleme icin)
- Tum servisler ucretsiz.

## Kamusal Taahhut

- OpenPDF, Wikipedia benzeri topluluk modelinden ilham alan dernek tipi kamusal fayda girisimi olarak konumlanir.
- Tum temel hizmetlerimizin her zaman ucretsiz kalacagi taahhut edilir.
- Topluluk destegi sadece altyapi ve surdurulebilirlik icin kullanilir.

## Bagis Kanallari

- GitHub Sponsors
- Patreon
- Buy Me a Coffee

`.env` bagis degiskenleri:

- `OPENPDF_DONATE_GITHUB`
- `OPENPDF_DONATE_PATREON`
- `OPENPDF_DONATE_BMAC`
- `OPENPDF_ORGANIZATION`
- `OPENPDF_FREE_FOREVER`

## Coklu Dil

20 dil desteklenir:

- en, zh, hi, es, fr, ar, bn, pt, ru, ur
- id, de, ja, sw, mr, te, tr, ta, vi, ko

Her arac icin SEO odakli aciklama metinleri `lang/*/openpdf.php` icinde bulunur.

SEO URL yapisi:

- `/{locale}/{tool-slug}/{locale-seo-suffix}`
- Ornek:
  - `/en/pdf-to-word/free-converter`
  - `/tr/pdf-to-word/ucretsiz-cevirici`

Site haritalari:

- Dinamik XML: `/sitemap.xml`
- Kullanici sayfasi: `/{locale}/{site-map-slug}`

## Admin Panel

- URL: `/admin`
- Filament kaynaklari:
  - Members (users)
  - Conversion Tasks
  - Uploaded Files

Admin olusturma:

```bash
php artisan db:seed --class=AdminUserSeeder
```

`.env` degiskenleri:

- `ADMIN_EMAIL`
- `ADMIN_PASSWORD`

## Kurulum

```bash
cp .env.example .env
php artisan key:generate
```

PostgreSQL baglantisini `.env` icinde duzenleyin:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=openpdf
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

Migration ve seed:

```bash
php artisan migrate
php artisan db:seed
```

Queue/Horizon:

```bash
php artisan horizon
```

Gelistirme sunucusu:

```bash
php artisan serve
```

NPM ile frontend baslatma:

```bash
npm install
npm run dev
```

## Google Login

`GOOGLE_CLIENT_ID` tanimlayin:

```env
GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
```

Frontend Google Identity tokenini backend'e yollar, backend `tokeninfo` ile dogrular ve kullaniciyi oturum acmis hale getirir.

## Backend Donusum Adaptorlari

Asagidaki komut satiri araclari varsa backend donusumlari calisir:

- `pdfunite` (merge_pdf)
- `gs` / Ghostscript (compress_pdf)
- `pdftoppm` (pdf_to_jpg)
- `libreoffice --headless` (word/pdf/excel donusumleri)
- `img2pdf` veya `magick` (jpg_to_pdf backend fallback)

Arac yoksa ilgili islem `failed` durumuna gecer ve hata mesaji task kaydina yazilir.

## Dizinler

- Frontend view: `resources/views/tool.blade.php`
- Site map page: `resources/views/site-map.blade.php`
- Sitemap XML template: `resources/views/sitemap-xml.blade.php`
- Frontend JS: `public/js/openpdf-app.js`
- Frontend CSS: `public/css/openpdf.css`
- SEO routing controller: `app/Http/Controllers/Web/ToolPageController.php`
- Sitemap XML controller: `app/Http/Controllers/Web/SitemapXmlController.php`
- API: `app/Http/Controllers/Api`
- Conversion queue job: `app/Jobs/ProcessConversionTaskJob.php`
- Conversion pipeline: `app/Services/Conversion/ConversionPipeline.php`
- Filament resources: `app/Filament/Resources`
