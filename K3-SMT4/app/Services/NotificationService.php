<?php

namespace App\Services;

use App\Mail\K3Notification;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NotificationService
{
    private const EMAIL_TYPES = [
        'document.submitted',
        'incident.reported',
        'capa.overdue',
    ];

    /**
     * Send notification to a single user.
     */
    public static function send(
        User $user,
        string $type,
        string $title,
        string $message,
        ?string $link = null,
        array $data = []
    ): Notification {
        $notification = Notification::create([
            'id'      => Str::uuid(),
            'user_id' => $user->id,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
            'data'    => !empty($data) ? $data : null,
        ]);

        if (in_array($type, self::EMAIL_TYPES, true) && $user->email) {
            Mail::to($user->email)->queue(new K3Notification($title, $message, $link));
        }

        return $notification;
    }

    /**
     * Send notification to all users with a specific role.
     */
    public static function sendToRole(
        string $role,
        string $type,
        string $title,
        string $message,
        ?string $link = null,
        array $data = []
    ): void {
        $users = User::where('role', $role)
            ->whereRaw('"is_active" = true')
            ->get();

        foreach ($users as $user) {
            self::send($user, $type, $title, $message, $link, $data);
        }
    }

    /**
     * Send notification to all users within a specific department.
     */
    public static function sendToDepartment(
        int $departmentId,
        string $type,
        string $title,
        string $message,
        ?string $link = null,
        array $data = []
    ): void {
        $users = User::whereHas('employee', function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId);
        })->whereRaw('"is_active" = true')->get();

        foreach ($users as $user) {
            self::send($user, $type, $title, $message, $link, $data);
        }
    }

    /**
     * Send notification to multiple roles at once.
     */
    public static function sendToRoles(
        array $roles,
        string $type,
        string $title,
        string $message,
        ?string $link = null,
        array $data = []
    ): void {
        $users = User::whereIn('role', $roles)
            ->whereRaw('"is_active" = true')
            ->get();

        foreach ($users as $user) {
            self::send($user, $type, $title, $message, $link, $data);
        }
    }
}