<x-filament-panels::page>
    <style>
        .sc-wrap {
            font-family: inherit;
        }

        .sc-grid2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        @media(max-width:768px) {
            .sc-grid2 {
                grid-template-columns: 1fr;
            }
        }

        .sc-card {
            background: var(--fi-bg, #fff);
            border: 1px solid var(--fi-border-color, #e5e7eb);
            border-radius: 12px;
            overflow: hidden;
        }

        .dark .sc-card {
            background: #111827;
            border-color: #1f2937;
        }

        .sc-card-head {
            padding: 12px 20px;
            border-bottom: 1px solid var(--fi-border-color, #e5e7eb);
            background: rgba(249, 250, 251, 0.8);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .dark .sc-card-head {
            background: rgba(17, 24, 39, 0.6);
            border-color: #1f2937;
        }

        .sc-card-head h3 {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            color: #111827;
        }

        .dark .sc-card-head h3 {
            color: #f9fafb;
        }

        .sc-card-body {
            padding: 0;
        }

        .sc-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 20px;
            border-bottom: 1px solid #f3f4f6;
        }

        .dark .sc-row {
            border-color: #1f2937;
        }

        .sc-row:last-child {
            border-bottom: none;
        }

        .sc-label {
            font-size: 13px;
            font-weight: 500;
            color: #111827;
        }

        .dark .sc-label {
            color: #f9fafb;
        }

        .sc-sublabel {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 1px;
        }

        .sc-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 99px;
            white-space: nowrap;
        }

        .sc-badge-ok {
            background: #dcfce7;
            color: #15803d;
        }

        .dark .sc-badge-ok {
            background: rgba(21, 128, 61, .15);
            color: #4ade80;
        }

        .sc-badge-err {
            background: #fee2e2;
            color: #dc2626;
        }

        .dark .sc-badge-err {
            background: rgba(220, 38, 38, .15);
            color: #f87171;
        }

        .sc-badge-gray {
            background: #f3f4f6;
            color: #6b7280;
        }

        .dark .sc-badge-gray {
            background: rgba(255, 255, 255, .05);
            color: #9ca3af;
        }

        /* Main tests card */
        .sc-tests {
            margin-bottom: 0;
        }

        .sc-tests-head {
            padding: 14px 20px;
            border-bottom: 1px solid #e5e7eb;
            background: rgba(249, 250, 251, 0.8);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .dark .sc-tests-head {
            background: rgba(17, 24, 39, 0.6);
            border-color: #1f2937;
        }

        .sc-tests-head-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .sc-tests-head h2 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }

        .dark .sc-tests-head h2 {
            color: #f9fafb;
        }

        .sc-badges {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        /* Progress */
        .sc-progress-wrap {
            padding: 12px 20px 8px;
            border-bottom: 1px solid #f3f4f6;
        }

        .dark .sc-progress-wrap {
            border-color: #1f2937;
        }

        .sc-progress-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .sc-progress-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #9ca3af;
        }

        .sc-progress-counts {
            display: flex;
            gap: 14px;
            font-size: 12px;
        }

        .sc-count-ok {
            color: #16a34a;
            font-weight: 600;
        }

        .sc-count-err {
            color: #dc2626;
            font-weight: 600;
        }

        .sc-count-pend {
            color: #9ca3af;
        }

        .sc-bar-track {
            height: 6px;
            background: #f3f4f6;
            border-radius: 99px;
            overflow: hidden;
        }

        .dark .sc-bar-track {
            background: #1f2937;
        }

        .sc-bar-fill {
            height: 100%;
            border-radius: 99px;
            transition: width .4s ease;
        }

        .sc-bar-ok {
            background: #22c55e;
        }

        .sc-bar-err {
            background: #ef4444;
        }

        /* Table */
        .sc-table-head {
            display: grid;
            grid-template-columns: 160px 52px 1fr 1fr 130px;
            gap: 0;
            padding: 8px 20px;
            border-bottom: 1px solid #f3f4f6;
            background: rgba(249, 250, 251, .4);
        }

        .dark .sc-table-head {
            border-color: #1f2937;
            background: rgba(255, 255, 255, .02);
        }

        .sc-th {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #9ca3af;
        }

        .sc-th-c {
            text-align: center;
        }

        .sc-th-r {
            text-align: right;
        }

        @media(max-width:1024px) {
            .sc-table-head {
                display: none;
            }
        }

        .sc-tool-row {
            display: grid;
            grid-template-columns: 160px 52px 1fr 1fr 130px;
            gap: 0;
            align-items: center;
            padding: 10px 20px;
            border-bottom: 1px solid #f3f4f6;
            transition: background .15s;
        }

        .dark .sc-tool-row {
            border-color: #1f2937;
        }

        .sc-tool-row:last-child {
            border-bottom: none;
        }

        .sc-tool-row:hover {
            background: rgba(249, 250, 251, .8);
        }

        .dark .sc-tool-row:hover {
            background: rgba(255, 255, 255, .02);
        }

        @media(max-width:1024px) {
            .sc-tool-row {
                grid-template-columns: 1fr auto;
                grid-template-rows: auto auto;
                gap: 8px;
            }

            .sc-status-col {
                display: none;
            }

            .sc-io-col {
                grid-column: 1/-1;
            }
        }

        .sc-tool-name {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
        }

        .dark .sc-tool-name {
            color: #f9fafb;
        }

        .sc-status-col {
            display: flex;
            justify-content: center;
        }

        .sc-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
        }

        .sc-dot-none {
            background: #e5e7eb;
        }

        .dark .sc-dot-none {
            background: #374151;
        }

        .sc-dot-ok {
            background: #dcfce7;
            color: #15803d;
        }

        .dark .sc-dot-ok {
            background: rgba(21, 128, 61, .2);
            color: #4ade80;
        }

        .sc-dot-err {
            background: #fee2e2;
            color: #dc2626;
        }

        .dark .sc-dot-err {
            background: rgba(220, 38, 38, .2);
            color: #f87171;
        }

        .sc-io {
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 0;
        }

        .sc-file-card {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 8px;
            min-width: 0;
            overflow: hidden;
        }

        .sc-file-card-gray {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .dark .sc-file-card-gray {
            background: rgba(255, 255, 255, .04);
            border-color: #374151;
        }

        .sc-file-card-green {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .dark .sc-file-card-green {
            background: rgba(21, 128, 61, .08);
            border-color: rgba(21, 128, 61, .2);
        }

        .sc-file-card-red {
            flex: 1;
            padding: 6px 10px;
            border-radius: 8px;
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .dark .sc-file-card-red {
            background: rgba(220, 38, 38, .08);
            border-color: rgba(220, 38, 38, .2);
        }

        .sc-file-name {
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .sc-file-name {
            color: #d1d5db;
        }

        .sc-file-size {
            font-size: 10px;
            color: #9ca3af;
            line-height: 1;
        }

        .sc-file-size-green {
            font-size: 10px;
            color: #16a34a;
            line-height: 1;
        }

        .dark .sc-file-size-green {
            color: #4ade80;
        }

        .sc-arrow {
            font-size: 14px;
            color: #d1d5db;
            flex-shrink: 0;
        }

        .dark .sc-arrow {
            color: #374151;
        }

        .sc-idle-text {
            font-size: 12px;
            color: #d1d5db;
            font-style: italic;
        }

        .dark .sc-idle-text {
            color: #374151;
        }

        .sc-err-text {
            font-size: 12px;
            color: #dc2626;
            font-weight: 500;
        }

        .dark .sc-err-text {
            color: #f87171;
        }

        .sc-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .sc-icon {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }
    </style>

    <div class="sc-wrap">

        {{-- Top row: Binary + Env --}}
        <div class="sc-grid2">
            <div class="sc-card">
                <div class="sc-card-head">
                    <h3>System Binary Check</h3>
                </div>
                <div class="sc-card-body">
                    @foreach($this->getSystemChecks() as $check)
                    <div class="sc-row">
                        <div>
                            <div class="sc-label">{{ $check['name'] }}</div>
                            <div class="sc-sublabel">{{ $check['description'] }}</div>
                        </div>
                        @if($check['installed'])
                        <span class="sc-badge sc-badge-ok">Installed</span>
                        @else
                        <span class="sc-badge sc-badge-err">Missing</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="sc-card">
                <div class="sc-card-head">
                    <h3>Environment</h3>
                </div>
                <div class="sc-card-body">
                    <div class="sc-row">
                        <div class="sc-sublabel">Server</div>
                        <div class="sc-label">{{ gethostname() }}</div>
                    </div>
                    <div class="sc-row">
                        <div class="sc-sublabel">OS</div>
                        <div class="sc-label">{{ PHP_OS_FAMILY }} / {{ php_uname('m') }}</div>
                    </div>
                    <div class="sc-row">
                        <div class="sc-sublabel">PHP</div>
                        <div class="sc-label">{{ PHP_VERSION }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tool Tests --}}
        @php
        $tools = [
        'compress_pdf' => 'Compress PDF',
        'word_to_pdf' => 'Word to PDF',
        'excel_to_pdf' => 'Excel to PDF',
        'pdf_to_word' => 'PDF to Word',
        'pdf_to_excel' => 'PDF to Excel',
        'merge_pdf' => 'Merge PDF',
        'pdf_to_jpg' => 'PDF to JPG',
        'jpg_to_pdf' => 'JPG to PDF',
        ];
        $summary = $this->getTestSummary();
        $allRan = $summary['total'] === count($tools);
        @endphp

        <div class="sc-card sc-tests">

            {{-- Header --}}
            <div class="sc-tests-head">
                <div class="sc-tests-head-left">
                    <h2>Tool Functional Tests</h2>
                    @if($summary['total'] > 0)
                    <div class="sc-badges">
                        @if($summary['passed'] > 0)
                        <span class="sc-badge sc-badge-ok">✓ {{ $summary['passed'] }} başarılı</span>
                        @endif
                        @if($summary['failed'] > 0)
                        <span class="sc-badge sc-badge-err">✗ {{ $summary['failed'] }} hata</span>
                        @endif
                        @if(count($tools) - $summary['total'] > 0)
                        <span class="sc-badge sc-badge-gray">– {{ count($tools) - $summary['total'] }} bekliyor</span>
                        @endif
                    </div>
                    @endif
                </div>
                <x-filament::button wire:click="runAllTests" wire:loading.attr="disabled" wire:target="runAllTests"
                    size="sm" color="primary" icon="heroicon-m-play-circle">
                    <span wire:loading.remove wire:target="runAllTests">Tümünü Test Et</span>
                    <span wire:loading wire:target="runAllTests">Çalışıyor...</span>
                </x-filament::button>
            </div>

            {{-- Progress bar (after run all) --}}
            @if($summary['total'] > 0)
            <div class="sc-progress-wrap">
                <div class="sc-progress-meta">
                    <span class="sc-progress-label">Test Raporu</span>
                    <div class="sc-progress-counts">
                        <span class="sc-count-ok">✓ {{ $summary['passed'] }} başarılı</span>
                        <span class="sc-count-err">✗ {{ $summary['failed'] }} hata</span>
                        <span class="sc-count-pend">– {{ count($tools) - $summary['total'] }} bekliyor</span>
                    </div>
                </div>
                <div class="sc-bar-track">
                    <div class="sc-bar-fill {{ $summary['failed'] > 0 ? 'sc-bar-err' : 'sc-bar-ok' }}"
                        style="width: {{ round(($summary['total'] / count($tools)) * 100) }}%"></div>
                </div>
            </div>
            @endif

            {{-- Table column headers --}}
            <div class="sc-table-head">
                <div class="sc-th">Araç</div>
                <div class="sc-th sc-th-c">Durum</div>
                <div class="sc-th">Input Dosyası</div>
                <div class="sc-th">Output Dosyası</div>
                <div class="sc-th sc-th-r">İşlem</div>
            </div>

            {{-- Tool rows --}}
            @foreach($tools as $key => $label)
            @php $result = $testResults[$key] ?? null; @endphp
            <div class="sc-tool-row">

                {{-- Name --}}
                <div class="sc-tool-name">{{ $label }}</div>

                {{-- Status --}}
                <div class="sc-status-col">
                    @if($result === null)
                    <span class="sc-dot sc-dot-none"></span>
                    @elseif($result['status'] === 'success')
                    <span class="sc-dot sc-dot-ok">✓</span>
                    @else
                    <span class="sc-dot sc-dot-err">✗</span>
                    @endif
                </div>

                {{-- Input --}}
                <div class="sc-io-col">
                    @if($result && $result['status'] === 'success')
                    <div class="sc-file-card sc-file-card-gray">
                        <svg class="sc-icon" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div style="min-width:0">
                            <div class="sc-file-name">{{ $result['input_name'] }}</div>
                            <div class="sc-file-size">{{ \Illuminate\Support\Number::fileSize($result['input_size']) }}
                            </div>
                        </div>
                    </div>
                    @elseif($result && $result['status'] === 'failed')
                    <div class="sc-file-card-red">
                        <div class="sc-err-text">{{ $result['error'] }}</div>
                    </div>
                    @else
                    <span class="sc-idle-text">—</span>
                    @endif
                </div>

                {{-- Output --}}
                <div>
                    @if($result && $result['status'] === 'success')
                    <div class="sc-file-card sc-file-card-green">
                        <svg class="sc-icon" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div style="min-width:0">
                            <div class="sc-file-name">{{ $result['output_name'] }}</div>
                            <div class="sc-file-size-green">{{
                                \Illuminate\Support\Number::fileSize($result['output_size']) }}</div>
                        </div>
                    </div>
                    @else
                    <span class="sc-idle-text">—</span>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="sc-actions">
                    @if($result && $result['status'] === 'success')
                    <x-filament::button size="xs" color="gray" icon="heroicon-m-arrow-down-tray"
                        wire:click="downloadResult('{{ $key }}')">
                    </x-filament::button>
                    @endif
                    <x-filament::button size="xs" color="primary" icon="heroicon-m-play"
                        wire:click="testTool('{{ $key }}')" wire:loading.attr="disabled"
                        wire:target="testTool('{{ $key }}'), runAllTests">
                        <span wire:loading.remove wire:target="testTool('{{ $key }}')">Test</span>
                        <span wire:loading wire:target="testTool('{{ $key }}')">...</span>
                    </x-filament::button>
                </div>
            </div>
            @endforeach

        </div>

    </div>
</x-filament-panels::page>