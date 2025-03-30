<x-filament-widgets::widget>
    <x-filament::section>
        @if (!$hasData)
            <div class="flex justify-center p-4">
                <span class="text-sm text-gray-400">No evaluation data</span>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
                @foreach ($criteriaDetails as $category => $details)
                    @php 
                        $averageScore = collect($details['data'])->avg('score');
                        $iconBg = match($category) {
                            'commitment' => 'bg-primary-100',
                            'knowledge' => 'bg-warning-100',
                            'teaching' => 'bg-success-100',
                            'management' => 'bg-info-100',
                            default => 'bg-gray-100'
                        };
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <!-- Header -->
                        <div class="flex items-center gap-3 p-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-full {{ $iconBg }} dark:bg-gray-700">
                                    <x-dynamic-component :component="$details['icon']" class="w-5 h-5 text-gray-600 dark:text-white" />
                                </div>
                                <h3 class="text-base font-medium text-gray-900 dark:text-white">{{ $details['name'] }}</h3>
                            </div>
                            <div class="ml-auto">
                                <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($averageScore, 1) }}</span>
                            </div>
                        </div>

                        <!-- Criteria List -->
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($details['metrics'] as $key => $label)
                                @php 
                                    $metricData = $details['data'][$key];
                                    $barColor = match(true) {
                                        $metricData['score'] >= 5 => 'bg-success-500',
                                        $metricData['score'] >= 4 => 'bg-primary-500',
                                        $metricData['score'] >= 3 => 'bg-warning-500',
                                        default => 'bg-danger-500'
                                    };
                                @endphp
                                <div class="p-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="text-sm text-gray-600 dark:text-white">{{ $label }}</span>
                                        <div class="flex items-center gap-4">
                                            <div class="w-24 h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full {{ $barColor }}" style="width: {{ ($metricData['score'] / 5) * 100 }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium w-4 text-right text-gray-900 dark:text-white">{{ number_format($metricData['score'], 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>