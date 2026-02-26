<x-filament-panels::page>
    <div class="space-y-6">
        <section class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">Google Login Setup</h2>
            <p class="mt-2 text-sm text-gray-600">
                Frontend Google Sign-In, <code>GOOGLE_CLIENT_ID</code> ile calisir. Backend gelen credential tokeni Google ile dogrular.
            </p>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-6">
            <h3 class="text-base font-semibold text-gray-900">1. Google Cloud Console'da dogru sayfaya git</h3>
            <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm text-gray-700">
                <li>Proje sec veya olustur.</li>
                <li>
                    <a class="text-primary-600 underline" href="https://console.cloud.google.com/apis/credentials">
                        APIs &amp; Services &gt; Credentials
                    </a>
                    sayfasina gir.
                </li>
                <li><strong>Create Credentials</strong> &gt; <strong>OAuth client ID</strong> sec.</li>
                <li>Application type: <strong>Web application</strong>.</li>
                <li>Authorized JavaScript origins alanina: <code>{{ $appUrl }}</code> ekle.</li>
                <li>Uretildiginde sana verilen deger <strong>Client ID</strong> olacak (token degil).</li>
            </ol>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-6">
            <h3 class="text-base font-semibold text-gray-900">2. OAuth Consent Screen ayari (gerekirse)</h3>
            <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm text-gray-700">
                <li>
                    <a class="text-primary-600 underline" href="https://console.cloud.google.com/apis/credentials/consent">
                        OAuth consent screen
                    </a>
                    ekranini ac.
                </li>
                <li>App name, support email ve developer contact email alanlarini doldur.</li>
                <li>Test moddaysan test kullanicilarini ekle.</li>
                <li>Production'a gececeksen app verification/publish adimlarini tamamla.</li>
            </ol>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-6">
            <h3 class="text-base font-semibold text-gray-900">3. .env Configuration</h3>
            <div class="mt-3 rounded-lg bg-gray-950 p-4 text-sm text-gray-100">
                <pre><code>GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com</code></pre>
            </div>
            <p class="mt-3 text-sm text-gray-600">
                Current value:
                <code>{{ $clientId ?: 'NOT_SET' }}</code>
            </p>
            <p class="mt-2 text-sm text-gray-600">
                Not: Bu proje icin <strong>Client Secret</strong> zorunlu degil. Google Identity Services sadece Client ID kullanir.
            </p>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-6">
            <h3 class="text-base font-semibold text-gray-900">4. Laravel config yenile</h3>
            <div class="mt-3 rounded-lg bg-gray-950 p-4 text-sm text-gray-100">
                <pre><code>php artisan config:clear
php artisan cache:clear</code></pre>
            </div>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-6">
            <h3 class="text-base font-semibold text-gray-900">5. Dogrulama</h3>
            <ul class="mt-3 list-disc space-y-2 pl-5 text-sm text-gray-700">
                <li>Public sayfada Google butonunun gorundugunu kontrol et.</li>
                <li>Login sonrasi <code>/api/auth/session</code> cevabinda <code>logged_in: true</code> beklenir.</li>
                <li>DB'de kullanicinin <code>google_id</code> alaninin doldugunu kontrol et.</li>
            </ul>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-6">
            <h3 class="text-base font-semibold text-gray-900">6. Sık hatalar ve cozumler</h3>
            <ul class="mt-3 list-disc space-y-2 pl-5 text-sm text-gray-700">
                <li><strong>origin_mismatch</strong>: Authorized JavaScript origins alanina test domainini (ornek: <code>{{ $appUrl }}</code>) eklemedin.</li>
                <li><strong>invalid_client</strong>: Yanlis Client ID kullaniyorsun veya farkli proje ID'si.</li>
                <li><strong>button gorunmuyor</strong>: <code>GOOGLE_CLIENT_ID</code> bos veya config cache temizlenmedi.</li>
                <li><strong>popup blocked</strong>: Tarayici popup engeli var, ayni domainde tekrar dene.</li>
            </ul>
        </section>
    </div>
</x-filament-panels::page>
