# OpenPDF (`openpdf.com.tr`)

Laravel-based, multilingual, open-source PDF conversion platform.

## Stack

- PHP 8.2+
- Laravel 12
- PostgreSQL
- Laravel Horizon (queue worker)
- FilamentPHP v5 (admin panel)
- Bootstrap 5 + Vue 3

## Quick Start

```bash
cp .env.example .env
php artisan key:generate
composer install
npm install
```

PostgreSQL `.env` example:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=openpdf
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

Install and seed:

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

## Runtime Model

### HTTP / UI
- Public SEO route: `/{locale}/{tool-slug}/{seo-suffix}`
- Public sitemap route: `/{locale}/{site-map-slug}`
- API: `/api/conversions`, `/api/auth/*`

### Conversion Flow
1. Frontend receives and validates uploaded files.
2. Visitor limits are validated on both frontend and backend.
3. By tool runtime:
   - Browser-first: client-side conversion, then only task logging to API (`source=browser`).
   - Backend: API creates task, Horizon processes queue job, output is written to `storage`.
4. Download URL is returned from the task payload.

## Tool Matrix (Client / Backend)

| Tool key | UI label | Runtime | Engine |
|---|---|---|---|
| `split_pdf` | Split PDF | Client (browser-first) | `pdf-lib` + `pdf.js` + `jszip` |
| `jpg_to_pdf` | JPG to PDF | Client (browser-first) | `jsPDF` |
| `merge_pdf` | Merge PDF | Backend | `pdfunite` |
| `compress_pdf` | Compress PDF | Backend | `gs` (Ghostscript) |
| `pdf_to_jpg` | PDF to JPG | Backend | `pdftoppm` |
| `pdf_to_word` | PDF to Word | Backend | `libreoffice --headless` (or `soffice --headless`) |
| `pdf_to_excel` | PDF to Excel | Backend | `libreoffice --headless` (or `soffice --headless`) |
| `word_to_pdf` | Word to PDF | Backend | `libreoffice --headless` (or `soffice --headless`) |
| `excel_to_pdf` | Excel to PDF | Backend | `libreoffice --headless` (or `soffice --headless`) |

Notes:
- `jpg_to_pdf` backend adapter exists in the pipeline (`img2pdf` or `magick`), but the public UI currently runs browser-first.
- `split_pdf` currently runs in browser instead of backend queue.

## Backend Binary Requirements

- `gs` (Ghostscript)
- `libreoffice` or `soffice` (LibreOffice headless)
- `pdfunite` (Poppler Utils)
- `pdftoppm` (Poppler Utils)
- `magick` (ImageMagick) or `img2pdf` for backend `jpg_to_pdf`

If a required binary is missing, related conversion tasks fail and the error is written to `conversion_tasks.error_message`.

### Ubuntu Installation (for `/admin/system-check`)

Install all required packages:

```bash
sudo apt update
sudo apt install -y ghostscript libreoffice poppler-utils imagemagick img2pdf
```

Verify installed binaries:

```bash
gs --version
libreoffice --version
pdfunite -v
pdftoppm -v
magick -version || convert -version
img2pdf --version
```

Notes:
- `pdfunite` and `pdftoppm` are provided by `poppler-utils`.
- `jpg_to_pdf` backend works when either `img2pdf` or ImageMagick is available.
- On Linux servers, `libreoffice` is typically used as the headless binary.

## Authentication and Limits

- Login is optional.
- Visitor limits: `100` files, `100MB` (config: `openpdf.visitor_limits`).
- Google login enables unlimited mode.
- Google config: `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`.
- Admin Google setup help: `/admin/google-login-help`.

Admin seed:

```bash
php artisan db:seed --class=AdminUserSeeder
```

Default credentials:
- Email: `a@a.com`
- Password: `236330`

## Locale and SEO

- 20 locales are supported (`config/openpdf.php` -> `locales`).
- Locale-specific SEO suffixes are configured in `config/openpdf.php` -> `route_slugs`.
- Dynamic sitemap XML: `/sitemap.xml`.
