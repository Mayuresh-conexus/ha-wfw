<x-filament-panels::page>
    {{-- ── Overview ─────────────────────────────────────────────── --}}
    <section class="ha-zone">
        <div class="ha-zone__header">
            <span class="ha-zone__title">Overview</span>
            <span class="ha-zone__badge">
                <span class="ha-dot"></span> Live · auto-refreshing
            </span>
            <div class="ha-zone__rule"></div>
        </div>

        @livewire(\App\Filament\Widgets\OverviewStats::class)
    </section>

    {{-- ── Trends ───────────────────────────────────────────────── --}}
    <section class="ha-zone">
        <div class="ha-zone__header">
            <span class="ha-zone__title">Trends</span>
            <span class="text-xs text-gray-400">Last 12 months</span>
            <div class="ha-zone__rule"></div>
        </div>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
            @livewire(\App\Filament\Widgets\PatientTrendWidget::class)
            @livewire(\App\Filament\Widgets\RecordsTrendWidget::class)
        </div>
    </section>

    {{-- ── Clinical Operations ──────────────────────────────────── --}}
    <section class="ha-zone">
        <div class="ha-zone__header">
            <span class="ha-zone__title">Clinical Operations</span>
            <span class="text-xs text-gray-400">Live queues &amp; workload</span>
            <div class="ha-zone__rule"></div>
        </div>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
            <div class="xl:col-span-1">
                @livewire(\App\Filament\Widgets\RecordsByTypeChart::class)
            </div>
            <div class="xl:col-span-2">
                @livewire(\App\Filament\Widgets\VolunteerWorkloadWidget::class)
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4">
            @livewire(\App\Filament\Widgets\RecentRecordsTable::class)
            @livewire(\App\Filament\Widgets\UpcomingCallsTable::class)
        </div>
    </section>
</x-filament-panels::page>
