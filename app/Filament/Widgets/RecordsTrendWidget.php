<?php

namespace App\Filament\Widgets;

use App\Models\Record;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Clinical encounters (records) logged per month — the operational throughput
 * signal, shown as bars next to the patient-growth line.
 */
class RecordsTrendWidget extends ChartWidget
{
    protected static ?string $heading = 'Clinical Records Logged';
    protected static ?string $description = 'Encounters captured over the last 12 months';
    protected static ?string $maxHeight = '260px';
    protected static ?string $pollingInterval = '120s';

    protected function getData(): array
    {
        [$labels, $data] = Cache::remember('ha.dashboard.records-trend', now()->addSeconds(120), function () {
            $labels = [];
            $data = [];

            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $labels[] = $month->format('M');
                $data[] = Record::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            }

            return [$labels, $data];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Records',
                    'data' => $data,
                    'backgroundColor' => 'rgba(2, 132, 199, 0.55)',
                    'hoverBackgroundColor' => 'rgba(2, 132, 199, 0.85)',
                    'borderRadius' => 6,
                    'borderSkipped' => false,
                    'maxBarThickness' => 28,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => ['precision' => 0],
                    'grid' => ['drawBorder' => false],
                ],
                'x' => [
                    'grid' => ['display' => false],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
