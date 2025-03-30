<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="flex justify-center items-center gap-2 w-full">
        @php
            $currentValue = $getState() ?? 0;
        @endphp
        
        @for ($i = 1; $i <= 5; $i++)
            <button
                type="button"
                wire:click="$set('{{ $getStatePath() }}', {{ $i }})"
                class="text-3xl leading-none focus:outline-none p-1 hover:scale-110 transition-transform"
            >
                <span style="font-family: 'Segoe UI Symbol', sans-serif; color: {{
                    $currentValue >= $i
                        ? match($currentValue) {
                            5 => '#22c55e',
                            4 => '#3b82f6',
                            3 => '#eab308',
                            2 => '#f97316',
                            default => '#ef4444'
                        }
                        : '#9ca3af'
                }}">
                    {{ $currentValue >= $i ? '★' : '☆' }}
                </span>
            </button>
        @endfor
    </div>
</x-dynamic-component>