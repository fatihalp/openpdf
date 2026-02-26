<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @foreach ($alternateUrls as $alternateUrl)
        <link rel="alternate" hreflang="{{ $alternateUrl['locale'] }}" href="{{ $alternateUrl['url'] }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $alternateUrls[0]['url'] ?? $canonicalUrl }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/openpdf.css') }}">
</head>
<body>
<div id="openpdf-app" class="openpdf-app" v-cloak>
    <header class="site-header sticky-top">
        <nav class="navbar navbar-expand-lg py-2">
            <div class="container">
                <a class="brand" :href="config.homeUrl">
                    <span class="logo-mark">OP</span>
                    <span class="brand-copy">
                        <strong>OpenPDF</strong>
                        <small>@{{ config.domain }}</small>
                    </span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                        <li class="nav-item"><a class="nav-link" href="#tools">@{{ config.i18n.nav.tools }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="#workspace">@{{ config.i18n.nav.workspace }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="#limits">@{{ config.i18n.nav.limits }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="#donate">@{{ config.i18n.nav.donate }}</a></li>
                        <li class="nav-item"><a class="nav-link" :href="config.siteMapUrl">@{{ config.i18n.footer.site_map }}</a></li>
                    </ul>
                    <div class="header-controls ms-lg-3 mt-3 mt-lg-0">
                        <select class="form-select form-select-sm locale-select" v-model="selectedLocale" @change="switchLocale">
                            <option v-for="item in config.locales" :key="item.code" :value="item.code">@{{ item.label }}</option>
                        </select>
                        <span class="auth-pill" :class="auth.logged_in ? 'auth-pill-user' : 'auth-pill-visitor'">
                            @{{ auth.logged_in ? auth.name : config.i18n.auth.visitor }}
                        </span>
                        <button v-if="auth.logged_in" type="button" class="btn btn-sm btn-outline-dark" @click="logout">
                            @{{ config.i18n.auth.logout }}
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero-section">
            <div class="container">
                <div class="hero-panel">
                    <div class="hero-main">
                        <p class="hero-kicker">@{{ config.i18n.hero.kicker }}</p>
                        <h1>@{{ selectedTool ? selectedTool.title : config.i18n.hero.title }}</h1>
                        <p class="hero-copy">@{{ selectedTool ? selectedTool.description : config.i18n.hero.description }}</p>
                        <p class="hero-seo" v-if="selectedTool">@{{ selectedTool.seo }}</p>
                        <div class="hero-cta">
                            <a href="#workspace" class="btn btn-dark btn-lg">@{{ config.i18n.workspace.convert }}</a>
                            <span class="hero-limit">@{{ visitorLimitLabel }}</span>
                        </div>
                    </div>
                    <aside class="hero-auth card border-0">
                        <div class="card-body">
                            <h2>@{{ config.i18n.auth.optional }}</h2>
                            <p>@{{ config.i18n.auth.hint }}</p>
                            <div id="googleButton"></div>
                            <small v-if="!config.googleClientId">Google Client ID missing.</small>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <section id="tools" class="section-block">
            <div class="container">
                <div class="section-heading">
                    <h2>@{{ config.i18n.nav.tools }}</h2>
                    <p>@{{ config.i18n.meta.description }}</p>
                </div>
                <div class="tool-grid">
                    <button
                        v-for="tool in tools"
                        :key="tool.key"
                        class="tool-card"
                        :class="{ active: selectedTool && selectedTool.key === tool.key }"
                        type="button"
                        @click="selectTool(tool.key)"
                    >
                        <span class="tool-icon"><i :class="iconForTool(tool.key)"></i></span>
                        <h3>@{{ tool.title }}</h3>
                        <p>@{{ tool.description }}</p>
                        <small>@{{ tool.seo }}</small>
                    </button>
                </div>
            </div>
        </section>

        <section id="workspace" class="section-block">
            <div class="container">
                <div class="workspace-grid">
                    <div class="workspace-main card border-0">
                        <div class="card-body">
                            <h3 class="workspace-title">@{{ selectedTool ? selectedTool.title : '' }}</h3>
                            <p class="workspace-subtitle">@{{ selectedTool ? selectedTool.description : '' }}</p>

                            <label class="dropzone" @dragover.prevent="dropHover = true" @dragleave.prevent="dropHover = false" @drop.prevent="onDrop" :class="{ hover: dropHover }">
                                <input type="file" :accept="acceptAttribute" multiple @change="onInputChange" hidden>
                                <span class="dropzone-icon"><i class="bi bi-cloud-arrow-up"></i></span>
                                <strong>@{{ config.i18n.workspace.drop_title }}</strong>
                                <small>@{{ config.i18n.workspace.drop_subtitle }}</small>
                            </label>

                            <div class="empty-state" v-if="files.length === 0">@{{ config.i18n.workspace.empty }}</div>

                            <div
                                v-if="selectedTool && selectedTool.key === 'jpg_to_pdf'"
                                class="file-grid"
                                ref="sortableGrid"
                            >
                                <article
                                    v-for="(file, index) in files"
                                    :key="file.id"
                                    class="file-card"
                                    :data-file-id="file.id"
                                >
                                    <div class="file-actions">
                                        <button type="button" class="icon-btn icon-btn-rotate" @click="rotate(index)" :title="config.i18n.workspace.rotate">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                        <button type="button" class="icon-btn icon-btn-remove" @click="remove(index)" :title="config.i18n.workspace.remove">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                    <div class="preview-box">
                                        <img :src="file.preview" :alt="file.name" :style="{ transform: `rotate(${file.rotation}deg)` }">
                                    </div>
                                    <p class="file-name">@{{ file.name }}</p>
                                </article>
                            </div>

                            <div v-else class="list-group list-files mt-3">
                                <div v-for="(file, index) in files" :key="file.id" class="list-group-item">
                                    <div>
                                        <strong>@{{ file.name }}</strong>
                                        <small>@{{ formatBytes(file.size) }}</small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" @click="remove(index)">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <aside class="workspace-side card border-0">
                        <div class="card-body">
                            <h4>@{{ config.i18n.workspace.settings }}</h4>

                            <div v-if="selectedTool && selectedTool.key === 'jpg_to_pdf'">
                                <div class="mb-3">
                                    <label class="form-label">@{{ config.i18n.workspace.orientation }}</label>
                                    <select class="form-select" v-model="options.orientation">
                                        <option value="portrait">@{{ config.i18n.workspace.orientation_portrait }}</option>
                                        <option value="landscape">@{{ config.i18n.workspace.orientation_landscape }}</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">@{{ config.i18n.workspace.page_size }}</label>
                                    <select class="form-select" v-model="options.pageSize">
                                        <option value="a4">A4</option>
                                        <option value="letter">Letter</option>
                                        <option value="a3">A3</option>
                                        <option value="fit">@{{ config.i18n.workspace.page_size_fit }}</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">@{{ config.i18n.workspace.margin }}</label>
                                    <select class="form-select" v-model.number="options.margin">
                                        <option :value="0">0</option>
                                        <option :value="8">8</option>
                                        <option :value="16">16</option>
                                        <option :value="24">24</option>
                                    </select>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="singleFile" v-model="options.singleFile">
                                    <label class="form-check-label" for="singleFile">@{{ config.i18n.workspace.single_file }}</label>
                                </div>
                            </div>

                            <button type="button" class="btn btn-danger w-100 convert-btn" @click="convert" :disabled="busy || files.length === 0">
                                <span v-if="busy" class="spinner-border spinner-border-sm me-2"></span>
                                @{{ config.i18n.workspace.convert }}
                            </button>
                            <p class="status-line">@{{ statusMessage }}</p>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <section class="section-block seo-copy-block">
            <div class="container">
                <div class="seo-copy">
                    <h2>@{{ selectedTool ? selectedTool.title : config.i18n.seo_section.title }}</h2>
                    <p>@{{ selectedTool ? selectedTool.seo : config.i18n.seo_section.description }}</p>
                    <p>@{{ config.i18n.seo_section.keywords }}</p>
                </div>
            </div>
        </section>

        <section id="limits" class="section-block limits-block">
            <div class="container">
                <h2 class="mb-3">@{{ config.i18n.limits.title }}</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="limit-card">
                            <h3>@{{ config.i18n.limits.visitor_title }}</h3>
                            <p>@{{ visitorFilesLabel }}</p>
                            <p>@{{ visitorSizeLabel }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="limit-card limit-card-accent">
                            <h3>@{{ config.i18n.limits.google_title }}</h3>
                            <p>@{{ config.i18n.limits.google_unlimited }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-block mission-block">
            <div class="container">
                <h2 class="mb-3">@{{ config.i18n.mission.title }}</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mission-card">
                            <h3>@{{ config.i18n.mission.free_forever_title }}</h3>
                            <p>@{{ config.i18n.mission.free_forever_text }}</p>
                            <span v-if="config.freeForever" class="mission-badge">Free Forever</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mission-card">
                            <h3>@{{ config.i18n.mission.association_title }}</h3>
                            <p>@{{ config.i18n.mission.association_text }}</p>
                            <p class="mb-0">@{{ config.i18n.mission.transparency_text }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="donate" class="section-block donation-block">
            <div class="container">
                <div class="donation-panel">
                    <div>
                        <h2>@{{ config.i18n.donate.title }}</h2>
                        <p>@{{ config.i18n.donate.description }}</p>
                    </div>
                    <div class="donation-links">
                        <a v-for="channel in config.donations" :key="channel.key" :href="channel.url" target="_blank" rel="noopener" class="btn btn-dark donation-btn">
                            <i :class="channel.icon" class="me-2"></i>@{{ channel.label }}
                        </a>
                    </div>
                    <small>@{{ config.i18n.donate.legal_note }}</small>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <p class="footer-brand">OpenPDF</p>
                    <p>@{{ config.i18n.footer.about }}</p>
                </div>
                <div class="footer-links">
                    <a :href="config.homeUrl">@{{ config.i18n.footer.home }}</a>
                    <a :href="config.siteMapUrl">@{{ config.i18n.footer.site_map }}</a>
                    <a :href="config.sitemapXmlUrl">@{{ config.i18n.footer.sitemap_xml }}</a>
                </div>
            </div>
        </div>
    </footer>
</div>

<script>window.OPENPDF_CONFIG = {!! $appConfigJson !!};</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@3.5.13/dist/vue.global.prod.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@1.7.9/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.2/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.4/Sortable.min.js"></script>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script src="{{ asset('js/openpdf-app.js') }}"></script>
</body>
</html>
