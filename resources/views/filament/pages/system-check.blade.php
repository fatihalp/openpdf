<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
            <x-filament::section>
                <x-slot name="heading">
                    System Binary Check
                </x-slot>

                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($this->getSystemChecks() as $check)
                    <li class="py-3 flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $check['name'] }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $check['description'] }}</span>
                        </div>
                        <div>
                            @if($check['installed'])
                            <x-filament::badge color="success">Installed</x-filament::badge>
                            @else
                            <x-filament::badge color="danger">Missing</x-filament::badge>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">
                    Environment Details
                </x-slot>

                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li class="py-3 flex justify-between">
                        <span class="text-sm font-medium text-gray-500 items-center">Server Name</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ gethostname() }}</span>
                    </li>
                    <li class="py-3 flex justify-between">
                        <span class="text-sm font-medium text-gray-500 items-center">OS / Architecture</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ PHP_OS_FAMILY }} ({{ PHP_OS
                            }}) -
                            {{ php_uname('m') }}</span>
                    </li>
                    <li class="py-3 flex justify-between">
                        <span class="text-sm font-medium text-gray-500 items-center">PHP Version</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ PHP_VERSION }}</span>
                    </li>
                </ul>
            </x-filament::section>
        </div>

        <x-filament::section>
            <x-slot name="heading">
                Tool Functional Tests
            </x-slot>
            <x-slot name="description">
                Verify conversion tools natively using bundled sample files.
            </x-slot>

            <ul class="divide-y divide-gray-200 dark:divide-gray-800">
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
                @endphp
                @foreach($tools as $key => $label)
                <li class="py-6 first:pt-0 last:pb-0">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span
                                    class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">{{
                                    $label }}</span>
                                @if (isset($testResults[$key]))
                                @if ($testResults[$key]['status'] === 'success')
                                <x-filament::badge color="success" size="xs">Başarılı</x-filament::badge>
                                @else
                                <x-filament::badge color="danger" size="xs">Hata</x-filament::badge>
                                @endif
                                @endif
                            </div>

                            @if (isset($testResults[$key]) && $testResults[$key]['status'] === 'success')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div
                                    class="flex items-center gap-4 p-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-white/5">
                                    <div class="flex-shrink-0 p-2 bg-gray-200 dark:bg-gray-800 rounded-lg">
                                        <x-filament::icon icon="heroicon-m-arrow-right-start-on-rectangle"
                                            class="h-5 w-5 text-gray-500" />
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span
                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">İşlenen
                                            Dosya</span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{
                                            $testResults[$key]['input_name'] }}</span>
                                        <span class="text-xs text-gray-400 font-medium whitespace-nowrap">{{
                                            \Illuminate\Support\Number::fileSize($testResults[$key]['input_size'])
                                            }}</span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center gap-4 p-3 rounded-xl bg-green-50/50 dark:bg-green-900/10 border border-green-100 dark:border-green-900/20">
                                    <div class="flex-shrink-0 p-2 bg-green-100 dark:bg-green-900/40 rounded-lg">
                                        <x-filament::icon icon="heroicon-m-arrow-right-end-on-rectangle"
                                            class="h-5 w-5 text-green-600 dark:text-green-400" />
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span
                                            class="text-[10px] font-bold text-green-600/70 dark:text-green-400/70 uppercase tracking-widest leading-none">Sonuç
                                            Dosyası</span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{
                                            $testResults[$key]['output_name'] }}</span>
                                        <span
                                            class="text-xs text-green-600/60 dark:text-green-400/60 font-medium whitespace-nowrap">{{
                                            \Illuminate\Support\Number::fileSize($testResults[$key]['output_size'])
                                            }}</span>
                                    </div>
                                </div>
                            </div>
                            @elseif (isset($testResults[$key]) && $testResults[$key]['status'] === 'failed')
                            <div
                                class="p-3 rounded-xl bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/20">
                                <p class="text-xs text-red-600 dark:text-red-400 font-medium leading-relaxed">
                                    {{ $testResults[$key]['error'] }}
                                </p>
                            </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 shrink-0 self-start md:self-center">
                            @if(isset($testResults[$key]) && $testResults[$key]['status'] === 'success')
                            <x-filament::button size="md" color="gray" icon="heroicon-m-arrow-down-tray"
                                wire:click="downloadResult('{{ $key }}')"
                                class="shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
                                İndir
                            </x-filament::button>
                            @endif

                            <x-filament::button size="md" color="primary" icon="heroicon-m-play"
                                wire:click="testTool('{{ $key }}')" wire:loading.attr="disabled"
                                wire:target="testTool('{{ $key }}')" class="shadow-sm">
                                <span wire:loading.remove wire:target="testTool('{{ $key }}')">Test Et</span>
                                <span wire:loading wire:target="testTool('{{ $key }}')">Çalışıyor...</span>
                            </x-filament::button>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </x-filament::section>
    </div>
</x-filament-panels::page>