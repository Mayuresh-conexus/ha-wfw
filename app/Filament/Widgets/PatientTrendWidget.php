<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Patient registrations over the trailing 12 months, rendered as a smooth,
 * gradient-filled area line — the primary growth signal on the console.
 */
class PatientTrendWidget extends ChartWidget
{
    protected static ?string $heading = 'Patient Registrations';
    protected static ?string $description = 'New patients onboarded over the last 12 months';
    protected static ?string $maxHeight = '260px';
    protected static ?string $pollingInterval = '120s';

    protected function getData(): array
    {
        [$labels, $data] = Cache::remember('ha.dashboard.patient-trend', now()->addSeconds(120), function () {
            $labels = [];
            $data = [];

            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $labels[] = $month->format('M');
                $data[] = Patient::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            }

            return [$labels, $data];
        });

        return [
            'datasets' => [
                [
                    'label' => 'New patients',
                    'data' => $data,
                    'borderColor' => '#F98713',
                    'backgroundColor' => 'rgba(249, 135, 19, 0.12)',
                    'fill' => 'start',
                    'tension' => 0.4,
                    'borderWidth' => 2,
                    'pointRadius' => 0,
                    'pointHoverRadius' => 5,
                    'pointHoverBackgroundColor' => '#F98713',
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
        return 'line';
    }
}
