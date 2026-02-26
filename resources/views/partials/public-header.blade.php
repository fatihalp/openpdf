@php
    $activeToolKey = $activeToolKey ?? null;
    $headerMenu = $headerMenu ?? [];
    $convertMenu = $headerMenu['convert_menu'] ?? [];
    $allToolsMenu = $headerMenu['all_tools_menu'] ?? [];
@endphp

<header class="op-header op-header-unified">
    <div class="container">
        <div class="op-apple-header">
            <a class="op-apple-brand" href="/" aria-label="OpenPDF home">
                <img class="op-brand-logo" src="{{ asset('img/openpdf-logo.svg') }}" alt="OpenPDF">
            </a>

            <nav class="op-main-nav">
                <a class="op-nav-link {{ $activeToolKey === 'merge_pdf' ? 'active' : '' }}" href="{{ $headerMenu['merge_url'] ?? '/' }}">Merge PDF</a>
                <a class="op-nav-link {{ $activeToolKey === 'split_pdf' ? 'active' : '' }}" href="{{ $headerMenu['split_url'] ?? ($headerMenu['all_tools_url'] ?? '/') }}">Split PDF</a>
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

                <div class="dropdown op-nav-dropdown-wrap">
                    <button class="op-nav-dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        All PDF Tools <i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu">
                        @foreach ($allToolsMenu as $tool)
                            <li><a class="dropdown-item" href="{{ $tool['url'] }}">{{ $tool['title'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </nav>

            <div class="op-auth-actions">
                <a class="op-login-link" href="{{ $headerMenu['login_url'] ?? '/admin/login' }}">Login</a>
                <a class="op-signup-link" href="{{ $headerMenu['signup_url'] ?? '/admin/login' }}">Sign up</a>
            </div>
        </div>
    </div>
</header>
