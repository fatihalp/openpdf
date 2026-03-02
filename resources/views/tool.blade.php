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
    @vite(['resources/css/app.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="openpdf-tool theme-dark">
    <div id="openpdf-app" v-cloak>
        @include('partials.public-header', ['headerMenu' => $headerMenu, 'activeToolKey' => $activeToolKey, 'locale' => $locale, 'localeLinks' => $localeLinks])

        <main class="op-converter-wrap">
            <input ref="fileInput" type="file" :accept="acceptAttribute" multiple @change="onInputChange" hidden>

            <div class="container" v-if="!resultReady && files.length === 0">
                <section class="op-converter op-converter-start">
                    <h1>@{{ selectedTool ? selectedTool.title : '' }}</h1>
                    <p class="op-converter-sub">@{{ selectedTool ? selectedTool.description : '' }}</p>

                    <div class="op-upload-area" @dragover.prevent="dropHover = true"
                        @dragleave.prevent="dropHover = false" @drop.prevent="onDrop" :class="{ hover: dropHover }">
                        <button type="button" class="op-select-button" @click="triggerFileSelect">
                            @{{ selectButtonLabel }}
                        </button>
                        <p class="op-upload-help">@{{ dropSubtitleLabel }}</p>
                        <p v-if="feedbackMessage" class="op-feedback" :class="`is-${feedbackType}`">@{{ feedbackMessage
                            }}</p>
                    </div>
                </section>
            </div>

            <div class="container-fluid op-work-layout" v-else-if="!resultReady">
                <section class="op-work-left" @dragover.prevent="dropHover = true"
                    @dragleave.prevent="dropHover = false" @drop.prevent="onDrop" :class="{ hover: dropHover }">
                    <button type="button" class="op-floating-add" @click="triggerFileSelect"
                        :title="config.i18n.workspace.add_more_files">
                        <span class="op-floating-count">@{{ files.length }}</span>
                        <i class="bi bi-plus-lg"></i>
                    </button>

                    <button type="button" class="op-floating-sort" v-if="!isSplitTool && files.length > 1"
                        @click="sortFilesByName" :title="config.i18n.workspace.sort_files">
                        <i class="bi bi-sort-alpha-down"></i>
                    </button>

                    <div v-if="isSplitTool" class="op-page-grid">
                        <article v-for="page in splitPages" :key="`page-${page.number}`" class="op-page-card"
                            :class="{ 'is-selected': page.selected, 'is-disabled': splitExtractMode === 'all' }"
                            @click="toggleSplitPage(page.number)">
                            <span class="op-page-check">
                                <i class="bi" :class="page.selected ? 'bi-check-lg' : 'bi-circle'"></i>
                            </span>
                            <div class="preview-box">
                                <img v-if="page.preview" :src="page.preview" :alt="`Page ${page.number}`">
                                <div v-else class="preview-placeholder">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <span>PDF</span>
                                </div>
                            </div>
                            <p class="op-page-number">@{{ page.number }}</p>
                        </article>
                    </div>

                    <div v-else class="file-grid op-file-grid-work" ref="sortableGrid">
                        <article v-for="(file, index) in files" :key="file.id" class="file-card"
                            :data-file-id="file.id">
                            <div class="file-actions">
                                <button type="button" class="icon-btn icon-btn-rotate" @click="rotate(index)"
                                    :title="config.i18n.workspace.rotate">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                                <button type="button" class="icon-btn icon-btn-remove" @click="remove(index)"
                                    :title="config.i18n.workspace.remove">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            <div class="preview-box">
                                <img v-if="file.preview" :src="file.preview" :alt="file.name"
                                    :style="{ transform: `rotate(${file.rotation}deg)` }">
                                <div v-else class="preview-placeholder">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <span>@{{ file.extension || 'FILE' }}</span>
                                </div>
                            </div>
                            <p class="file-name">@{{ file.name }}</p>
                        </article>
                    </div>
                </section>

                <aside class="op-work-right">
                    <h2>@{{ selectedTool ? selectedTool.title : '' }}</h2>

                    <div class="op-info-box">
                        <i class="bi bi-info-circle"></i>
                        <p>@{{ sidebarHintText }}</p>
                    </div>

                    <p v-if="feedbackMessage" class="op-feedback op-feedback-side" :class="`is-${feedbackType}`">@{{
                        feedbackMessage }}</p>

                    <div class="op-settings op-settings-side" v-if="isSplitTool">
                        <div class="op-split-tabs">
                            <button type="button" class="op-split-tab" :class="{ active: splitViewMode === 'range' }"
                                @click="setSplitViewMode('range')">
                                <i class="bi bi-braces"></i>
                                <span>@{{ config.i18n.workspace.split_mode_range }}</span>
                            </button>
                            <button type="button" class="op-split-tab" :class="{ active: splitViewMode === 'pages' }"
                                @click="setSplitViewMode('pages')">
                                <i class="bi bi-grid-3x3-gap"></i>
                                <span>@{{ config.i18n.workspace.split_mode_pages }}</span>
                            </button>
                            <button type="button" class="op-split-tab op-split-tab-disabled" disabled>
                                <i class="bi bi-arrows-fullscreen"></i>
                                <span>@{{ config.i18n.workspace.split_mode_size }}</span>
                                <i class="bi bi-crown-fill op-split-crown"></i>
                            </button>
                        </div>

                        <div class="op-split-block">
                            <label class="form-label">@{{ config.i18n.workspace.split_extract_mode }}</label>
                            <div class="op-split-actions">
                                <button type="button" class="op-split-action"
                                    :class="{ active: splitExtractMode === 'all' }" @click="setSplitExtractMode('all')">
                                    @{{ config.i18n.workspace.split_extract_all }}
                                </button>
                                <button type="button" class="op-split-action"
                                    :class="{ active: splitExtractMode === 'selected' }"
                                    @click="setSplitExtractMode('selected')">
                                    @{{ config.i18n.workspace.split_select_pages }}
                                </button>
                            </div>
                        </div>

                        <div class="op-split-block" v-if="splitExtractMode === 'selected'">
                            <label class="form-label">@{{ config.i18n.workspace.split_pages_to_extract }}</label>
                            <input type="text" class="form-control" v-model="splitSelectionInput"
                                :placeholder="config.i18n.workspace.split_pages_placeholder"
                                @change="applySplitSelectionInput">
                        </div>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="splitMergeExtracted"
                                v-model="splitMergeExtracted">
                            <label class="form-check-label" for="splitMergeExtracted">@{{
                                config.i18n.workspace.split_merge_into_one }}</label>
                        </div>
                    </div>

                    <div class="op-settings op-settings-side"
                        v-else-if="selectedTool && selectedTool.key === 'jpg_to_pdf'">
                        <div class="mb-2">
                            <label class="form-label">@{{ config.i18n.workspace.orientation }}</label>
                            <select class="form-select" v-model="options.orientation">
                                <option value="portrait">@{{ config.i18n.workspace.orientation_portrait }}</option>
                                <option value="landscape">@{{ config.i18n.workspace.orientation_landscape }}</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">@{{ config.i18n.workspace.page_size }}</label>
                            <select class="form-select" v-model="options.pageSize">
                                <option value="a4">A4</option>
                                <option value="letter">Letter</option>
                                <option value="a3">A3</option>
                                <option value="fit">@{{ config.i18n.workspace.page_size_fit }}</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">@{{ config.i18n.workspace.margin }}</label>
                            <select class="form-select" v-model.number="options.margin">
                                <option :value="0">0</option>
                                <option :value="8">8</option>
                                <option :value="16">16</option>
                                <option :value="24">24</option>
                            </select>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="singleFile"
                                v-model="options.singleFile">
                            <label class="form-check-label" for="singleFile">@{{ config.i18n.workspace.single_file
                                }}</label>
                        </div>
                    </div>

                    <div class="op-work-cta">
                        <button type="button" class="btn op-primary-btn op-convert-btn op-convert-panel-btn"
                            @click="convert" :disabled="busy || !canConvert">
                            <span v-if="busy" class="spinner-border spinner-border-sm me-2"></span>
                            <span>@{{ panelConvertLabel }}</span>
                            <i class="bi bi-arrow-right-circle"></i>
                        </button>
                    </div>
                </aside>
            </div>

            <div class="container op-result-stage" v-else>
                <h2>@{{ resultTitle }}</h2>
                <div class="op-result-actions">
                    <button type="button" class="op-result-back" @click="resetWorkspace"
                        :title="config.i18n.workspace.back_to_editor">
                        <i class="bi bi-arrow-left"></i>
                    </button>
                    <a class="op-result-download" :href="resultUrl" :download="resultFileName">
                        <i class="bi bi-download"></i>
                        <span>@{{ resultButtonLabel }}</span>
                    </a>
                </div>
            </div>
        </main>
    </div>

    @include('partials.footer')
    @include('partials.floating-controls', ['locale' => $locale, 'localeLinks' => $localeLinks])

    <script>window.OPENPDF_CONFIG = {!! $appConfigJson!!};</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.5.13/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.7.9/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.2/dist/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.4/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="{{ asset('js/openpdf-app.js') }}"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
</body>

</html>
