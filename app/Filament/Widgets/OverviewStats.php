<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use App\Models\Program;
use App\Models\Project;
use App\Models\Record;
use App\Models\ScheduledCall;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

/**
 * Top-of-console KPI band. Each card carries a value, a period-over-period
 * delta (▲/▼ vs last month) and a sparkline — the hallmark of an enterprise
 * control plane. Aggregates are cached briefly so the dashboard never hammers
 * the database on every poll/refresh.
 */
class OverviewStats extends StatsOverviewWidget
{
    protected static ?string $pollingInterval = '60s';

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $d = Cache::remember('ha.dashboard.overview', now()->addSeconds(60), fn () => $this->compute());

        [$pPct, $pDir] = $this->pctDelta($d['patientsThisMonth'], $d['patientsLastMonth']);
        [$rPct, $rDir] = $this->pctDelta($d['recordsThisMonth'], $d['recordsLastMonth']);

        return [
            Stat::make('Total Patients', number_format($d['totalPatients']))
                ->description($d['patientsThisMonth'] . ' new this month')
                ->descriptionIcon($this->trendIcon($pDir))
                ->chart($d['patientsSeries'])
                ->color($this->trendColor($pDir)),

            Stat::make('New This Month', number_format($d['patientsThisMonth']))
                ->description($this->deltaLabel($pPct, $pDir) . ' vs last month')
                ->descriptionIcon($this->trendIcon($pDir))
                ->chart($d['patientsSeries'])
                ->color($this->trendColor($pDir)),

            Stat::make('Active Patients', number_format($d['activePatients']))
                ->description($d['inactivePatients'] . ' inactive')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),

            Stat::make('Clinical Records', number_format($d['totalRecords']))
                ->description($d['recordsThisMonth'] . ' logged this month')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->chart($d['recordsSeries'])
                ->color('primary'),

            Stat::make('Records This Month', number_format($d['recordsThisMonth']))
                ->description($this->deltaLabel($rPct, $rDir) . ' vs last month')
                ->descriptionIcon($this->trendIcon($rDir))
                ->chart($d['recordsSeries'])
                ->color($this->trendColor($rDir)),

            Stat::make('Active Projects', number_format($d['activeProjects']))
                ->description($d['activePrograms'] . ' active programs')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('primary'),

            Stat::make('Scheduled Calls', number_format($d['upcomingCalls']))
                ->description($d['overdueCalls'] . ' overdue')
                ->descriptionIcon('heroicon-m-phone-arrow-up-right')
                ->color($d['overdueCalls'] > 0 ? 'warning' : 'success'),

            Stat::make('Care Team', number_format($d['activeUsers']))
                ->description('Active volunteers & GPs')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('gray'),
        ];
    }

    private function compute(): array
    {
        $patientsSeries = $this->monthlyNew(Patient::class);
        $recordsSeries = $this->monthlyNew(Record::class);

        $totalPatients = Patient::count();
        $activePatients = Patient::where('is_active', true)->count();
        $upcomingCalls = ScheduledCall::whereDate('schedule_date', '>=', today())->count();
        $overdueCalls = ScheduledCall::whereDate('schedule_date', '<', today())
            ->whereNotIn('status', ['completed', 'done', 'cancelled'])
            ->count();

        return [
            'patientsSeries'    => $patientsSeries,
            'recordsSeries'     => $recordsSeries,
            'totalPatients'     => $totalPatients,
            'activePatients'    => $activePatients,
            'inactivePatients'  => max(0, $totalPatients - $activePatients),
            'patientsThisMonth' => end($patientsSeries) ?: 0,
            'patientsLastMonth' => $patientsSeries[count($patientsSeries) - 2] ?? 0,
            'totalRecords'      => Record::count(),
            'recordsThisMonth'  => end($recordsSeries) ?: 0,
            'recordsLastMonth'  => $recordsSeries[count($recordsSeries) - 2] ?? 0,
            'activeProjects'    => Project::where('is_active', true)->count(),
            'activePrograms'    => Program::where('is_active', true)->count(),
            'upcomingCalls'     => $upcomingCalls,
            'overdueCalls'      => $overdueCalls,
            'activeUsers'       => User::where('isactive', true)->count(),
        ];
    }

    /** New rows per calendar month for the last N months (sparkline series). */
    private function monthlyNew(string $modelClass, int $months = 8): array
    {
        $series = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $series[] = $modelClass::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return $series;
    }

    private function pctDelta(int $current, int $previous): array
    {
        if ($previous === 0) {
            return [$current > 0 ? 100 : 0, $current > 0 ? 'up' : 'flat'];
        }

        $pct = (int) round((($current - $previous) / $previous) * 100);

        return [$pct, $current > $previous ? 'up' : ($current < $previous ? 'down' : 'flat')];
    }

    private function deltaLabel(int $pct, string $dir): string
    {
        $arrow = $dir === 'up' ? '+' : ($dir === 'down' ? '−' : '');

        return $arrow . abs($pct) . '%';
    }

    private function trendIcon(string $dir): string
    {
        return match ($dir) {
            'up'   => 'heroicon-m-arrow-trending-up',
            'down' => 'heroicon-m-arrow-trending-down',
            default => 'heroicon-m-minus-small',
        };
    }

    private function trendColor(string $dir): string
    {
        return match ($dir) {
            'up'   => 'success',
            'down' => 'danger',
            default => 'gray',
        };
    }
}
