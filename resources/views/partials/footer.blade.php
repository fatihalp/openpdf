@php
$locale = app()->getLocale();
$homeUrl = \App\Support\ToolCatalog::isLocale($locale) ? \App\Support\ToolCatalog::homeUrl($locale) : '/';
@endphp

<footer
    class="bg-[#f5f5f7] border-t border-[#d2d2d7] text-[#6e6e73] text-[12px] font-[-apple-system,BlinkMacSystemFont,'SF_Pro_Text','Helvetica_Neue',Arial,sans-serif]">
    <div class="max-w-[980px] mx-auto px-4 sm:px-6 pb-6 pt-8">
        <div class="flex flex-col md:flex-row justify-between pb-6 border-b border-[#d2d2d7] gap-8">
            <div class="flex flex-col gap-3">
                <a href="{{ $homeUrl }}"
                    class="text-[#1d1d1f] font-semibold text-xl flex items-center gap-2 hover:opacity-80 transition-opacity">
                    <span style="letter-spacing: -0.02em;">OpenPDF</span>
                </a>
                <p class="max-w-xs leading-relaxed">{{ trans('openpdf.footer.about') }}</p>

                <a href="https://github.com/fatihalp/openpdf" target="_blank"
                    class="mt-2 inline-flex items-center gap-1.5 hover:text-[#1d1d1f] transition-colors w-max">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path
                            d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8" />
                    </svg>
                    Fork me
                </a>
            </div>

            <div class="flex gap-12 sm:gap-24">
                <div>
                    <h3 class="text-[#1d1d1f] font-semibold mb-3">Developers</h3>
                    <ul class="flex flex-col gap-2.5">
                        <li><a href="/developer/login"
                                class="hover:text-[#1d1d1f] hover:underline transition-colors">Register as Developer</a>
                        </li>
                        <li><a href="/developer"
                                class="hover:text-[#1d1d1f] hover:underline transition-colors">Developer Portal</a></li>
                        <li><a href="/docs/api"
                                class="hover:text-[#1d1d1f] hover:underline transition-colors">Documentation</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-[#1d1d1f] font-semibold mb-3">Support</h3>
                    <ul class="flex flex-col gap-2.5">
                        <li><a href="#" class="hover:text-[#1d1d1f] hover:underline transition-colors">Donate</a></li>
                        <li><a href="{{ route('legal.privacy', ['locale' => $locale]) }}"
                                class="hover:text-[#1d1d1f] hover:underline transition-colors">{{
                                trans('openpdf.footer.privacy_policy') }}</a></li>
                        <li><a href="{{ route('legal.terms', ['locale' => $locale]) }}"
                                class="hover:text-[#1d1d1f] hover:underline transition-colors">{{
                                trans('openpdf.footer.terms_of_service') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="pt-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
            <div>
                Copyright &copy; {{ date('Y') }} {{ config('app.url') ? parse_url(config('app.url'), PHP_URL_HOST) :
                'OpenPDF' }}. All rights reserved.
            </div>
            <div>
                {{ trans('openpdf.footer.model') }}
            </div>
        </div>
    </div>
</footer>
