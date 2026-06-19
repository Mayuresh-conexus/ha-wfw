@php
    $storageColor = $infra['storageUsedPct'] >= 90 ? 'bg-rose-500' : ($infra['storageUsedPct'] >= 75 ? 'bg-amber-500' : 'bg-primary-500');
@endphp

<x-filament-widgets::widget>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

        {{-- Database --}}
        <div class="ha-card">
            <div class="flex items-center justify-between">
                <span class="ha-card__title">Database</span>
                @if ($db['ok'])
                    <span class="ha-pill ha-pill--ok"><span class="ha-dot"></span> Operational</span>
                @else
                    <span class="ha-pill ha-pill--down"><span class="ha-dot"></span> Unreachable</span>
                @endif
            </div>
            <div class="mt-3">
                <div class="ha-metric">
                    <span class="ha-metric__label">Latency</span>
                    <span class="ha-metric__value">{{ $db['latency'] !== null ? $db['latency'] . ' ms' : '—' }}</span>
                </div>
                <div class="ha-metric">
                    <span class="ha-metric__label">Connection</span>
                    <span class="ha-metric__value">{{ $db['connection'] }}</span>
                </div>
                <div class="ha-metric">
                    <span class="ha-metric__label">Patients · Records</span>
                    <span class="ha-metric__value">{{ number_format($counts['patients']) }} · {{ number_format($counts['records']) }}</span>
                </div>
            </div>
        </div>

        {{-- Runtime / Environment --}}
        <div class="ha-card">
            <div class="flex items-center justify-between">
                <span class="ha-card__title">Runtime</span>
                @if ($runtime['env'] === 'production')
                    <span class="ha-pill ha-pill--ok"><span class="ha-dot"></span> {{ ucfirst($runtime['env']) }}</span>
                @else
                    <span class="ha-pill ha-pill--warn"><span class="ha-dot"></span> {{ ucfirst($runtime['env']) }}</span>
                @endif
            </div>
            <div class="mt-3">
                <div class="ha-metric">
                    <span class="ha-metric__label">Debug mode</span>
                    <span class="ha-metric__value">
                        @if ($runtime['debug'])
                            <span class="ha-pill ha-pill--down">ON</span>
                        @else
                            <span class="ha-pill ha-pill--ok">OFF</span>
                        @endif
                    </span>
                </div>
                <div class="ha-metric">
                    <span class="ha-metric__label">PHP</span>
                    <span class="ha-metric__value">{{ $runtime['php'] }}</span>
                </div>
                <div class="ha-metric">
                    <span class="ha-metric__label">Laravel</span>
                    <span class="ha-metric__value">{{ $runtime['laravel'] }}</span>
                </div>
            </div>
        </div>

        {{-- Infrastructure --}}
        <div class="ha-card">
            <span class="ha-card__title">Infrastructure</span>
            <div class="mt-3">
                <div class="ha-metric">
                    <span class="ha-metric__label">Cache · Queue</span>
                    <span class="ha-metric__value">{{ $infra['cache'] }} · {{ $infra['queue'] }}</span>
                </div>
                <div class="ha-metric">
                    <span class="ha-metric__label">Session</span>
                    <span class="ha-metric__value">{{ $infra['session'] }}</span>
                </div>
                <div class="pt-3">
                    <div class="mb-1.5 flex items-center justify-between">
                        <span class="ha-metric__label">Storage used</span>
                        <span class="ha-metric__value">{{ $infra['storageUsedPct'] }}%</span>
                    </div>
                    <div class="ha-bar">
                        <div class="ha-bar__fill {{ $storageColor }}" style="width: {{ max(2, $infra['storageUsedPct']) }}%"></div>
                    </div>
                    <div class="mt-1.5 text-xs text-gray-400">{{ $infra['storageFree'] }} free of {{ $infra['storageTotal'] }}</div>
                </div>
            </div>
        </div>

        {{-- Catalog / data volume --}}
        <div class="ha-card">
            <span class="ha-card__title">Catalog</span>
            <div class="mt-3">
                <div class="ha-metric">
                    <span class="ha-metric__label">Symptoms</span>
                    <span class="ha-metric__value">{{ number_format($counts['symptoms']) }}</span>
                </div>
                <div class="ha-metric">
                    <span class="ha-metric__label">Questions</span>
                    <span class="ha-metric__value">{{ number_format($counts['questions']) }}</span>
                </div>
                <div class="ha-metric">
                    <span class="ha-metric__label">Medicines</span>
                    <span class="ha-metric__value">{{ number_format($counts['medicines']) }}</span>
                </div>
                <div class="ha-metric">
                    <span class="ha-metric__label">Team members</span>
                    <span class="ha-metric__value">{{ number_format($counts['users']) }}</span>
                </div>
            </div>
        </div>

    </div>
</x-filament-widgets::widget>
