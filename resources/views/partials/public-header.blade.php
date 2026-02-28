@php
    $activeToolKey = $activeToolKey ?? null;
    $headerMenu = $headerMenu ?? [];
    $convertMenu = $headerMenu['convert_menu'] ?? [];
    $allToolsUrl = $headerMenu['all_tools_url'] ?? '/';
@endphp

<header class="op-header op-header-unified">
    <div class="container">
        <div class="op-apple-header">
            <a class="op-apple-brand" href="/" aria-label="OpenPDF home">
                <svg class="op-brand-logo" viewBox="0 0 220 48" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="openpdfLogoTitle">
                    <title id="openpdfLogoTitle">OpenPDF</title>
                    <rect x="4.5" y="4.5" width="39" height="39" rx="11" fill="currentColor" fill-opacity="0.08" stroke="currentColor" stroke-opacity="0.3"/>
                    <path d="M14.5 18.1L18.1 14.9L20.8 16.8L23.8 14.5L26.5 16.4L29.4 14.4L33.5 18.5V33.5H14.5V18.1Z" fill="currentColor" fill-opacity="0.18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M19 22.2H28.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    <path d="M19 26.5H27.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    <path d="M19 30.8H25.7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    <text x="56" y="31" fill="currentColor" font-size="27" font-weight="700" font-family="-apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Segoe UI', sans-serif">OpenPDF</text>
                </svg>
            </a>

            <nav class="op-main-nav">
                <a class="op-nav-link {{ $activeToolKey === 'merge_pdf' ? 'active' : '' }}" href="{{ $headerMenu['merge_url'] ?? '/' }}">Merge PDF</a>
                <a class="op-nav-link {{ $activeToolKey === 'split_pdf' ? 'active' : '' }}" href="{{ $headerMenu['split_url'] ?? $allToolsUrl }}">Split PDF</a>
                <a class="op-nav-link {{ $activeToolKey === 'compress_pdf' ? 'active' : '' }}" href="{{ $headerMenu['compress_url'] ?? '/' }}">Compress PDF</a>

                <div class="dropdown op-nav-dropdown-wrap">
                    <button class="op-nav-dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Convert PDF <i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu">
                        @foreach ($convertMenu as $tool)
                            <li><a class="dropdown-item" href="{{ $tool['url'] }}">{{ $tool['title'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <a class="op-nav-link op-nav-link-tools {{ $activeToolKey === null ? 'active' : '' }}" href="{{ $allToolsUrl }}">All PDF Tools</a>
            </nav>

            <div class="op-auth-actions">
                <a class="op-login-link" href="{{ $headerMenu['login_url'] ?? '/admin/login' }}">Login</a>
                <a class="op-signup-link" href="{{ $headerMenu['signup_url'] ?? '/admin/login' }}">Sign up</a>
            </div>
        </div>
    </div>
</header>
