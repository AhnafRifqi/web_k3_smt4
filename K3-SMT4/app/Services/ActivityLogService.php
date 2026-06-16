<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService
{
    /**
     * Log an activity entry.
     */
    public static function log(
        string $action,
        string $module,
        string $description,
        $subject = null,
        array $oldValues = [],
        array $newValues = []
    ): ActivityLog {
        $user = auth()->user();

        return ActivityLog::create([
            'user_id'     => $user?->id,
            'user_name'   => $user?->name ?? 'System',
            'user_role'   => $user?->role ?? 'system',
            'action'      => $action,
            'module'      => $module,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'  => $subject ? $subject->id : null,
            'description' => $description,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'old_values'  => !empty($oldValues) ? $oldValues : null,
            'new_values'  => !empty($newValues) ? $newValues : null,
            'created_at'  => now(),
        ]);
    }
}