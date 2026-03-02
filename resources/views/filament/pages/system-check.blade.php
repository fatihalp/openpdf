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


        .sc-card-head {
            padding: 12px 20px;
            border-bottom: 1px solid var(--fi-border-color, #e5e7eb);
            background: rgba(249, 250, 251, 0.8);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }


        .sc-card-head h3 {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            color: #111827;
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


        .sc-row:last-child {
            border-bottom: none;
        }

        .sc-label {
            font-size: 13px;
            font-weight: 500;
            color: #111827;
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


        .sc-badge-err {
            background: #fee2e2;
            color: #dc2626;
        }


        .sc-badge-gray {
            background: #f3f4f6;
            color: #6b7280;
        }


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


        .sc-badges {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .sc-progress-wrap {
            padding: 12px 20px 8px;
            border-bottom: 1px solid #f3f4f6;
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

        .sc-table-head {
            display: grid;
            grid-template-columns: 160px 52px 1fr 1fr 130px;
            gap: 0;
            padding: 8px 20px;
            border-bottom: 1px solid #f3f4f6;
            background: rgba(249, 250, 251, .4);
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


        .sc-tool-row:last-child {
            border-bottom: none;
        }

        .sc-tool-row:hover {
            background: rgba(249, 250, 251, .8);
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


        .sc-dot-ok {
            background: #dcfce7;
            color: #15803d;
        }


        .sc-dot-err {
            background: #fee2e2;
            color: #dc2626;
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


        .sc-file-card-green {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }


        .sc-file-card-red {
            flex: 1;
            padding: 6px 10px;
            border-radius: 8px;
            background: #fef2f2;
            border: 1px solid #fecaca;
        }


        .sc-file-name {
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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


        .sc-arrow {
            font-size: 14px;
            color: #d1d5db;
            flex-shrink: 0;
        }


        .sc-idle-text {
            font-size: 12px;
            color: #d1d5db;
            font-style: italic;
        }


        .sc-err-text {
            font-size: 12px;
            color: #dc2626;
            font-weight: 500;
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

        .sc-guide {
            margin-bottom: 16px;
        }

        .sc-guide-body {
            padding: 14px 20px 20px;
            display: grid;
            gap: 14px;
        }

        .sc-guide-step {
            display: grid;
            gap: 8px;
        }

        .sc-guide-step-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .sc-guide-title {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
        }

        .sc-guide-desc {
            font-size: 11px;
            color: #6b7280;
        }

        .sc-code {
            margin: 0;
            padding: 12px 14px;
            border-radius: 10px;
            background: #0f172a;
            color: #e2e8f0;
            font-size: 12px;
            line-height: 1.55;
            border: 1px solid #1e293b;
            overflow-x: auto;
        }

        .sc-copy-btn {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            color: #111827;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            cursor: pointer;
        }

        .sc-copy-btn:hover {
            background: #e5e7eb;
        }
    </style>

    <div class="sc-wrap">
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
        <div class="sc-card sc-guide">
            <div class="sc-card-head">
                <h3>Ubuntu 22.04 / 24.04 Install Commands</h3>
            </div>
            <div class="sc-guide-body">
                @foreach($this->getUbuntuInstallGuide() as $step)
                <div class="sc-guide-step" x-data="{ command: @js($step['command']) }">
                    <div class="sc-guide-step-head">
                        <div>
                            <div class="sc-guide-title">{{ $step['title'] }}</div>
                            <div class="sc-guide-desc">{{ $step['description'] }}</div>
                        </div>
                        <button type="button" class="sc-copy-btn"
                            x-on:click="navigator.clipboard.writeText(command)">
                            Copy
                        </button>
                    </div>
                    <pre class="sc-code">{{ $step['command'] }}</pre>
                </div>
                @endforeach
            </div>
        </div>

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
            <div class="sc-tests-head">
                <div class="sc-tests-head-left">
                    <h2>Tool Functional Tests</h2>
                    @if($summary['total'] > 0)
                    <div class="sc-badges">
                        @if($summary['passed'] > 0)
                        <span class="sc-badge sc-badge-ok">✓ {{ $summary['passed'] }} passed</span>
                        @endif
                        @if($summary['failed'] > 0)
                        <span class="sc-badge sc-badge-err">✗ {{ $summary['failed'] }} failed</span>
                        @endif
                        @if(count($tools) - $summary['total'] > 0)
                        <span class="sc-badge sc-badge-gray">– {{ count($tools) - $summary['total'] }} pending</span>
                        @endif
                    </div>
                    @endif
                </div>
                <x-filament::button wire:click="runAllTests" wire:loading.attr="disabled" wire:target="runAllTests"
                    size="sm" color="primary" icon="heroicon-m-play-circle">
                    <span wire:loading.remove wire:target="runAllTests">Run All Tests</span>
                    <span wire:loading wire:target="runAllTests">Running...</span>
                </x-filament::button>
            </div>
            @if($summary['total'] > 0)
            <div class="sc-progress-wrap">
                <div class="sc-progress-meta">
                    <span class="sc-progress-label">Test Report</span>
                    <div class="sc-progress-counts">
                        <span class="sc-count-ok">✓ {{ $summary['passed'] }} passed</span>
                        <span class="sc-count-err">✗ {{ $summary['failed'] }} failed</span>
                        <span class="sc-count-pend">– {{ count($tools) - $summary['total'] }} pending</span>
                    </div>
                </div>
                <div class="sc-bar-track">
                    <div class="sc-bar-fill {{ $summary['failed'] > 0 ? 'sc-bar-err' : 'sc-bar-ok' }}"
                        style="width: {{ round(($summary['total'] / count($tools)) * 100) }}%"></div>
                </div>
            </div>
            @endif
            <div class="sc-table-head">
                <div class="sc-th">Tool</div>
                <div class="sc-th sc-th-c">Status</div>
                <div class="sc-th">Input File</div>
                <div class="sc-th">Output File</div>
                <div class="sc-th sc-th-r">Action</div>
            </div>
            @foreach($tools as $key => $label)
            @php $result = $testResults[$key] ?? null; @endphp
            <div class="sc-tool-row">
                <div class="sc-tool-name">{{ $label }}</div>
                <div class="sc-status-col">
                    @if($result === null)
                    <span class="sc-dot sc-dot-none"></span>
                    @elseif($result['status'] === 'success')
                    <span class="sc-dot sc-dot-ok">✓</span>
                    @else
                    <span class="sc-dot sc-dot-err">✗</span>
                    @endif
                </div>
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
                <div class="sc-actions">
                    @if($result && $result['status'] === 'success')
                    <x-filament::button size="xs" color="gray" icon="heroicon-m-arrow-down-tray"
                        wire:click="downloadResult('{{ $key }}')">
                    </x-filament::button>
                    @endif
                    <x-filament::button size="xs" color="primary" icon="heroicon-m-play"
                        wire:click="testTool('{{ $key }}')" wire:loading.attr="disabled"
                        wire:target="testTool('{{ $key }}'), runAllTests">
                        <span wire:loading.remove wire:target="testTool('{{ $key }}')">Run</span>
                        <span wire:loading wire:target="testTool('{{ $key }}')">...</span>
                    </x-filament::button>
                </div>
            </div>
            @endforeach

        </div>

    </div>
</x-filament-panels::page>
