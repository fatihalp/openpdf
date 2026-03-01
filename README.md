# OpenPDF (`openpdf.com.tr`)

Laravel tabanli, cok dilli, acik kaynak PDF donusum platformu.

## Stack

- PHP 8.2+
- Laravel 12
- PostgreSQL
- Laravel Horizon (queue worker)
- FilamentPHP v5 (admin panel)
- Bootstrap 5 + Vue 3

## Hızlı Başlangıç

```bash
cp .env.example .env
php artisan key:generate
composer install
npm install
```

`.env` PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=openpdf
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

Kurulum:

```bash
php artisan migrate
php artisan db:seed
```

Development:

```bash
php artisan serve
npm run dev
php artisan horizon
```

## Çalışma Modeli

### HTTP/UI
- Public SEO route: `/{locale}/{tool-slug}/{seo-suffix}`
- Public map route: `/{locale}/{site-map-slug}`
- API: `/api/conversions`, `/api/auth/*`

### Dönüşüm Akışı
1. Frontend dosyayı alir, dogrular.
2. Visitor limitleri frontend + backend tarafinda kontrol edilir.
3. Islem tipine gore:
   - Browser-first: client-side donusum, sonra sadece task logu API'ye yazilir (`source=browser`).
   - Backend: API task olusturur, Horizon queue job isler, sonuc `storage`'a yazilir.
4. Cikti indirme linki task uzerinden verilir.

## Araç Matrisi (Client / Backend)

| Tool key | UI label | Runtime | Engine |
|---|---|---|---|
| `split_pdf` | Split PDF | Client (browser-first) | `pdf-lib` + `pdf.js` + `jszip` |
| `jpg_to_pdf` | JPG to PDF | Client (browser-first) | `jsPDF` |
| `merge_pdf` | Merge PDF | Backend | `pdfunite` |
| `compress_pdf` | Compress PDF | Backend | `gs` (Ghostscript) |
| `pdf_to_jpg` | PDF to JPG | Backend | `pdftoppm` |
| `pdf_to_word` | PDF to Word | Backend | `libreoffice --headless` (veya `soffice --headless`) |
| `pdf_to_excel` | PDF to Excel | Backend | `libreoffice --headless` (veya `soffice --headless`) |
| `word_to_pdf` | Word to PDF | Backend | `libreoffice --headless` (veya `soffice --headless`) |
| `excel_to_pdf` | Excel to PDF | Backend | `libreoffice --headless` (veya `soffice --headless`) |

Not:
- `jpg_to_pdf` backend adaptoru pipeline'da mevcuttur (`img2pdf` veya `magick`), ancak mevcut public UI browser-first calisir.
- `split_pdf` su an backend queue yerine browser'da calisir.

## Backend Binary Gereksinimleri

- `pdfunite`
- `gs`
- `pdftoppm`
- `libreoffice` veya `soffice` (LibreOffice)
- (opsiyonel) `img2pdf` veya `magick`

Eksik binary varsa ilgili task `failed` olur ve hata mesaji `conversion_tasks.error_message` alanina yazilir.

### LibreOffice headless kurulum

macOS (Homebrew):

```bash
brew install --cask libreoffice
soffice --version
```

Ubuntu:

```bash
sudo apt update
sudo apt install -y libreoffice
libreoffice --version
```

Not:
- Bu proje macOS tarafinda `soffice` binary'sini da destekler.
- CI/production Linux ortamlarda `libreoffice` binary adi yaygindir.

## Kimlik / Limit

- Uyelik zorunlu degil.
- Visitor limiti: `100` dosya, `100MB` (config: `openpdf.visitor_limits`).
- Google login: limitsiz mod.
- Google config: `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`.
- Admin Google setup help: `/admin/google-login-help`

Admin seed:

```bash
php artisan db:seed --class=AdminUserSeeder
```

Varsayilan:
- email: `a@a.com`
- password: `236330`

## Dil / SEO

- 20 locale desteklenir (`config/openpdf.php` -> `locales`).
- Locale bazli SEO suffix: `config/openpdf.php` -> `route_slugs`.
- Dinamik sitemap XML: `/sitemap.xml`
