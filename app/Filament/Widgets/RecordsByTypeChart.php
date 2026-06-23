<?php

namespace App\Filament\Widgets;

use App\Models\Record;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

/**
 * Distribution of clinical records by type — a quick read on the case mix.
 */
class RecordsByTypeChart extends ChartWidget
{
    protected static ?string $heading = 'Records by Type';
    protected static ?string $description = 'Case mix across all records';
    protected static ?string $maxHeight = '260px';
    protected static ?string $pollingInterval = '120s';

    protected function getData(): array
    {
        $rows = Cache::remember('ha.dashboard.records-by-type', now()->addSeconds(120), function () {
            // Group by the real column (keeps ONLY_FULL_GROUP_BY happy), then fold
            // null/empty types into a single "Unspecified" bucket in PHP.
            $merged = [];

            Record::query()
                ->selectRaw('record_type, COUNT(*) as c')
                ->groupBy('record_type')
                ->pluck('c', 'record_type')
                ->each(function ($count, $type) use (&$merged) {
                    $label = ($type === null || $type === '') ? 'Unspecified' : $type;
                    $merged[$label] = ($merged[$label] ?? 0) + (int) $count;
                });

            arsort($merged);

            return array_slice($merged, 0, 8, true);
        });

        // Brand-only palette: alternating tints/shades of navy (#093E62) and
        // orange (#F98713) so up to 8 slices stay distinguishable on-brand.
        $palette = ['#093E62', '#F98713', '#1B6B9C', '#FBA34A', '#3E8FBF', '#C76A0E', '#7FB4D6', '#FCC78A'];

        return [
            'datasets' => [[
                'data' => array_values($rows),
                'backgroundColor' => array_slice($palette, 0, max(1, count($rows))),
                'borderWidth' => 0,
                'hoverOffset' => 6,
            ]],
            'labels' => array_keys($rows),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['position' => 'bottom']],
            'maintainAspectRatio' => false,
            'cutout' => '62%',
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
