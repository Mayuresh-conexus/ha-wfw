<?php

namespace App\Filament\Widgets;

use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Question;
use App\Models\Record;
use App\Models\Symptom;
use App\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Operational "system status" panel, AWS-console style: live DB health,
 * runtime/environment facts, infrastructure drivers, storage utilisation and
 * data-volume / catalog counts — an at-a-glance read on the whole system.
 */
class SystemHealthWidget extends Widget
{
    protected static string $view = 'filament.widgets.system-health';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        // --- Live DB health (cheap, measured every render) ---
        $dbOk = true;
        $dbLatency = null;
        try {
            $start = microtime(true);
            DB::select('select 1');
            $dbLatency = round((microtime(true) - $start) * 1000, 1); // ms
        } catch (\Throwable $e) {
            $dbOk = false;
        }

        // --- Storage utilisation on the app's storage volume ---
        $path = storage_path();
        $free = @disk_free_space($path) ?: 0;
        $total = @disk_total_space($path) ?: 0;
        $usedPct = $total > 0 ? (int) round((1 - $free / $total) * 100) : 0;

        // --- Counts are cached so the panel never hammers the DB on poll ---
        $counts = Cache::remember('ha.dashboard.system-counts', now()->addSeconds(120), fn () => [
            'patients'  => Patient::count(),
            'records'   => Record::count(),
            'users'     => User::count(),
            'symptoms'  => Symptom::count(),
            'questions' => Question::count(),
            'medicines' => Medicine::count(),
        ]);

        return [
            'db' => [
                'ok' => $dbOk,
                'latency' => $dbLatency,
                'connection' => config('database.default'),
            ],
            'runtime' => [
                'env' => app()->environment(),
                'debug' => (bool) config('app.debug'),
                'php' => PHP_VERSION,
                'laravel' => app()->version(),
                'timezone' => config('app.timezone'),
            ],
            'infra' => [
                'cache' => config('cache.default'),
                'queue' => config('queue.default'),
                'session' => config('session.driver'),
                'storageUsedPct' => $usedPct,
                'storageFree' => $this->humanBytes($free),
                'storageTotal' => $this->humanBytes($total),
            ],
            'counts' => $counts,
        ];
    }

    private function humanBytes(float $bytes): string
    {
        if ($bytes <= 0) {
            return '—';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = (int) floor(log($bytes, 1024));
        $i = min($i, count($units) - 1);

        return round($bytes / (1024 ** $i), 1) . ' ' . $units[$i];
    }
}
