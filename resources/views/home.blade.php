<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trans('openpdf.meta.title') }}</title>
    <meta name="description" content="{{ trans('openpdf.meta.description') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/openpdf.css') }}">
</head>

<body>
    <div id="openpdf-app" class="app-shell" v-cloak>
        <header class="site-header sticky-top">
            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <a class="navbar-brand" href="/">
                        <img src="{{ asset('img/openpdf-logo.svg') }}" alt="OpenPDF Logo"
                            style="height: 32px; width: auto;">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                        aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="mainNav">
                        <ul class="navbar-nav ms-auto me-3">
                            <li class="nav-item"><a class="nav-link" href="#tools">@{{ config.i18n.nav.tools }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="#workspace">@{{ config.i18n.nav.workspace
                                    }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="#policy">@{{ config.i18n.nav.limits }}</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#about">@{{ config.i18n.nav.about }}</a></li>
                        </ul>
                        <form method="post" action="{{ route('locale.switch') }}"
                            class="d-flex align-items-center gap-2">
                            @csrf
                            <select name="locale" class="form-select form-select-sm" style="min-width: 130px;"
                                onchange="this.form.submit()">
                                @foreach ($i18n = trans('openpdf.locales') as $code => $label)
                                <option value="{{ $code }}" @selected(app()->getLocale() === $code)>{{ $label }}
                                </option>
                                @endforeach
                            </select>
                            <span class="badge" :class="auth.logged_in ? 'text-bg-success' : 'text-bg-secondary'">@{{
                                auth.logged_in ? auth.name : config.i18n.auth.visitor }}</span>
                            <button v-if="auth.logged_in" type="button" class="btn btn-sm btn-outline-secondary"
                                @click="logout">@{{ config.i18n.auth.logout }}</button>
                        </form>
                    </div>
                </div>
            </nav>
        </header>

        <main>
            <section class="hero-section">
                <div class="container">
                    <div class="hero-wrap">
                        <div class="hero-content">
                            <p class="kicker">@{{ config.i18n.hero.kicker }}</p>
                            <h1>@{{ config.i18n.hero.title }}</h1>
                            <p class="hero-text">@{{ config.i18n.hero.description }}</p>
                            <div class="chip-row">
                                <span class="chip">@{{ config.i18n.hero.badge_open }}</span>
                                <span class="chip">@{{ config.i18n.hero.badge_no_membership }}</span>
                                <span class="chip chip-accent">@{{ visitorLimitLabel }}</span>
                            </div>
                        </div>
                        <div class="hero-auth card border-0 shadow-sm">
                            <div class="card-body">
                                <h2>@{{ config.i18n.auth.optional }}</h2>
                                <p class="text-muted mb-3">@{{ config.i18n.auth.hint }}</p>
                                <div id="googleButton"></div>
                                <small class="text-muted" v-if="!config.googleClientId">Google Client ID tanimli
                                    degil.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="tools" class="section-space">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div>
                            <h2 class="section-title">@{{ config.i18n.nav.tools }}</h2>
                            <p class="text-muted mb-0">@{{ config.i18n.meta.description }}</p>
                        </div>
                    </div>
                    <div class="tool-grid">
                        <button v-for="tool in tools" :key="tool.key" class="tool-card"
                            :class="{ active: selectedTool && selectedTool.key === tool.key }" type="button"
                            @click="selectTool(tool.key)">
                            <h3>@{{ tool.title }}</h3>
                            <p>@{{ tool.description }}</p>
                            <small class="seo">@{{ tool.seo }}</small>
                        </button>
                    </div>
                </div>
            </section>

            <section id="workspace" class="section-space">
                <div class="container">
                    <div class="workspace-grid">
                        <div class="workspace-main card border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="mb-1">@{{ selectedTool ? selectedTool.title : '' }}</h3>
                                <p class="text-muted">@{{ selectedTool ? selectedTool.description : '' }}</p>

                                <label class="dropzone" @dragover.prevent="dropHover = true"
                                    @dragleave.prevent="dropHover = false" @drop.prevent="onDrop"
                                    :class="{ hover: dropHover }">
                                    <input type="file" :accept="acceptAttribute" multiple @change="onInputChange"
                                        hidden>
                                    <p class="drop-title">@{{ config.i18n.workspace.drop_title }}</p>
                                    <p class="drop-sub">@{{ config.i18n.workspace.drop_subtitle }}</p>
                                </label>

                                <div class="empty-box" v-if="files.length === 0">No files selected.</div>

                                <div v-if="selectedTool && selectedTool.key === 'jpg_to_pdf'" class="file-grid">
                                    <article v-for="(file, index) in files" :key="file.id" class="file-card"
                                        draggable="true" @dragstart="startDrag(file.id)" @dragover.prevent
                                        @drop.prevent="dropOn(file.id)">
                                        <div class="actions">
                                            <button type="button" class="btn btn-sm btn-danger rounded-circle"
                                                @click="rotate(index)">↻</button>
                                            <button type="button" class="btn btn-sm btn-light rounded-circle"
                                                @click="remove(index)">✕</button>
                                        </div>
                                        <div class="preview">
                                            <img :src="file.preview" :alt="file.name"
                                                :style="{ transform: `rotate(${file.rotation}deg)` }">
                                        </div>
                                        <p class="name">@{{ file.name }}</p>
                                    </article>
                                </div>

                                <div v-else class="list-group mt-3">
                                    <div v-for="(file, index) in files" :key="file.id"
                                        class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>@{{ file.name }}</strong>
                                            <small class="d-block text-muted">@{{ formatBytes(file.size) }}</small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            @click="remove(index)">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <aside class="workspace-side card border-0 shadow-sm">
                            <div class="card-body">
                                <h4 class="mb-3">@{{ config.i18n.workspace.settings }}</h4>

                                <div v-if="selectedTool && selectedTool.key === 'jpg_to_pdf'">
                                    <div class="mb-3">
                                        <label class="form-label">Orientation</label>
                                        <select class="form-select" v-model="options.orientation">
                                            <option value="portrait">Portrait</option>
                                            <option value="landscape">Landscape</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Page Size</label>
                                        <select class="form-select" v-model="options.pageSize">
                                            <option value="a4">A4</option>
                                            <option value="letter">Letter</option>
                                            <option value="a3">A3</option>
                                            <option value="fit">Fit Image</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Margin</label>
                                        <select class="form-select" v-model.number="options.margin">
                                            <option :value="0">0</option>
                                            <option :value="8">8</option>
                                            <option :value="16">16</option>
                                            <option :value="24">24</option>
                                        </select>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="singleFile"
                                            v-model="options.singleFile">
                                        <label class="form-check-label" for="singleFile">Single output PDF</label>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-danger w-100" @click="convert"
                                    :disabled="busy || files.length === 0">
                                    @{{ config.i18n.workspace.convert }}
                                </button>
                                <p class="status mt-3 mb-0">@{{ statusMessage }}</p>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>

            <section id="policy" class="section-space policy-section">
                <div class="container">
                    <h2 class="section-title">@{{ config.i18n.limits.title }}</h2>
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h3>@{{ config.i18n.limits.visitor_title }}</h3>
                                    <ul class="mb-0">
                                        <li>@{{ visitorFilesLabel }}</li>
                                        <li>@{{ visitorSizeLabel }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-danger shadow-sm h-100">
                                <div class="card-body">
                                    <h3>@{{ config.i18n.limits.google_title }}</h3>
                                    <p class="mb-0">@{{ config.i18n.limits.google_unlimited }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer id="about" class="site-footer">
            <div class="container py-5">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-4 mb-md-0 text-center text-md-start">
                        <h3 class="mb-2">@{{ config.domain }}</h3>
                        <p class="mb-1 text-muted">@{{ config.i18n.footer.about }}</p>
                        <p class="mb-1 text-muted">@{{ config.i18n.footer.domain }}</p>
                        <p class="mb-0 text-muted">@{{ config.i18n.footer.model }}</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <a href="https://github.com/fatihalp/openpdf" target="_blank" rel="noopener noreferrer"
                            class="btn btn-dark rounded-pill d-inline-flex align-items-center gap-2 px-4 py-2 shadow-sm transition-transform hover-lift"
                            style="transition: transform 0.2s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.2s ease;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                class="bi bi-github" viewBox="0 0 16 16">
                                <path
                                    d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8" />
                            </svg>
                            <span class="fw-semibold">Fork me on GitHub</span>
                        </a>
                    </div>
                </div>
            </div>
            <style>
                .hover-lift:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
                }
            </style>
        </footer>
    </div>

    <script>window.OPENPDF_CONFI = {!! $appConf!};</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.5.13/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.7.9/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.2/dist/jspdf.umd.min.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="{{ asset('js/openpdf-app.js') }}"></script>
</body>

</html>