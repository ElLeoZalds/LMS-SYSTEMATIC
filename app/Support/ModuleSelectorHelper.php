<?php

namespace App\Support;

use App\Models\Module;
use App\Models\Training;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ModuleSelectorHelper
{
    public const STATUS_COMPLETED = 'Completado';

    public const STATUS_IN_PROGRESS = 'En curso';

    public const STATUS_PENDING = 'Pendiente';

    public static function loadForTraining(Training $training, ?int $courseId = null): array
    {
        $courseId = $courseId ?? $training->course?->course_id ?? $training->course_id;

        $modules = Module::where('course_id', $courseId)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return self::annotate($modules, $training);
    }

    public static function annotate(Collection $modules, Training $training): array
    {
        $sorted = $modules->sortBy('order')->values();
        $today = Carbon::today();
        $defaultModuleId = null;

        if ($sorted->isEmpty()) {
            return ['modules' => $sorted, 'defaultModuleId' => null];
        }

        $currentIndex = self::resolveCurrentModuleIndex($sorted, $training, $today);

        foreach ($sorted as $index => $module) {
            $module->module_status = self::resolveStatus($index, $currentIndex, $training, $today);

            if ($module->module_status === self::STATUS_IN_PROGRESS) {
                $defaultModuleId = $module->id;
            }
        }

        return ['modules' => $sorted, 'defaultModuleId' => $defaultModuleId];
    }

    private static function resolveCurrentModuleIndex(Collection $sorted, Training $training, Carbon $today): ?int
    {
        $count = $sorted->count();

        if ($count === 0) {
            return null;
        }

        if (! $training->start_date || ! $training->end_date) {
            return 0;
        }

        $start = Carbon::parse($training->start_date)->startOfDay();
        $end = Carbon::parse($training->end_date)->endOfDay();

        if ($today->lt($start)) {
            return null;
        }

        if ($today->gt($end)) {
            return $count - 1;
        }

        $totalDays = max(1, $start->diffInDays($end) + 1);

        for ($i = 0; $i < $count; $i++) {
            $moduleStart = (clone $start)->addDays((int) floor($i * $totalDays / $count));
            $moduleEnd = $i === $count - 1
                ? $end
                : (clone $start)->addDays((int) floor(($i + 1) * $totalDays / $count) - 1)->endOfDay();

            if ($today->between($moduleStart, $moduleEnd)) {
                return $i;
            }
        }

        return $count - 1;
    }

    private static function resolveStatus(int $index, ?int $currentIndex, Training $training, Carbon $today): string
    {
        if ($currentIndex === null) {
            return self::STATUS_PENDING;
        }

        if ($training->start_date && $training->end_date) {
            $end = Carbon::parse($training->end_date)->endOfDay();

            if ($today->gt($end)) {
                return self::STATUS_COMPLETED;
            }
        }

        if ($index < $currentIndex) {
            return self::STATUS_COMPLETED;
        }

        if ($index > $currentIndex) {
            return self::STATUS_PENDING;
        }

        return self::STATUS_IN_PROGRESS;
    }
}
