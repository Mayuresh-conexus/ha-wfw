<x-filament-widgets::widget>
    <div class="ha-card h-full">
        <div class="flex items-center justify-between">
            <span class="ha-card__title">Volunteer Workload</span>
            <span class="text-xs text-gray-400">Top by records handled</span>
        </div>

        <div class="mt-4 space-y-3.5">
            @forelse ($rows as $row)
                <div>
                    <div class="mb-1 flex items-center justify-between gap-3">
                        <span class="truncate text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $row['name'] }}
                        </span>
                        <span class="flex shrink-0 items-center gap-2 text-xs">
                            <span class="ha-pill ha-pill--ok">{{ $row['records'] }} records</span>
                            @if ($row['calls'] > 0)
                                <span class="ha-pill ha-pill--warn">{{ $row['calls'] }} calls</span>
                            @endif
                        </span>
                    </div>
                    <div class="ha-bar">
                        <div class="ha-bar__fill" style="width: {{ max(4, $row['pct']) }}%"></div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <x-heroicon-o-users class="h-8 w-8 text-gray-300 dark:text-gray-600" />
                    <p class="mt-2 text-sm text-gray-400">No records assigned to volunteers yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-filament-widgets::widget>
