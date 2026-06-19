<?php

namespace App\Filament\Widgets;

use App\Models\Record;
use App\Models\ScheduledCall;
use App\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;

/**
 * Volunteer workload / round-robin balance: who is carrying how many records
 * and how many upcoming calls, with a relative-load bar. Surfaces imbalance
 * at a glance the way a queue-depth panel would in an ops console.
 */
class VolunteerWorkloadWidget extends Widget
{
    protected static string $view = 'filament.widgets.volunteer-workload';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $rows = Cache::remember('ha.dashboard.volunteer-workload', now()->addSeconds(120), function () {
            $records = Record::query()
                ->whereNotNull('volunteerid')
                ->selectRaw('volunteerid, COUNT(*) as c')
                ->groupBy('volunteerid')
                ->orderByDesc('c')
                ->limit(6)
                ->pluck('c', 'volunteerid');

            if ($records->isEmpty()) {
                return [];
            }

            $calls = ScheduledCall::query()
                ->whereDate('schedule_date', '>=', today())
                ->whereNotNull('volunteer_id')
                ->selectRaw('volunteer_id, COUNT(*) as c')
                ->groupBy('volunteer_id')
                ->pluck('c', 'volunteer_id');

            $names = User::whereIn('id', $records->keys())->pluck('name', 'id');
            $max = max(1, (int) $records->max());

            return $records->map(fn ($count, $vid) => [
                'name' => $names[$vid] ?? ('User #' . $vid),
                'records' => (int) $count,
                'calls' => (int) ($calls[$vid] ?? 0),
                'pct' => (int) round($count / $max * 100),
            ])->values()->all();
        });

        return ['rows' => $rows];
    }
}
