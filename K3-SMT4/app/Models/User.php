<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active', 'avatar_url',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Role Helpers
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isSupervisorK3(): bool { return $this->role === 'supervisor_k3'; }
    public function isAuditor(): bool { return $this->role === 'auditor'; }
    public function isKaryawan(): bool { return $this->role === 'karyawan'; }
    public function canManage(): bool { return in_array($this->role, ['admin', 'supervisor_k3']); }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'supervisor_k3' => 'Supervisor K3',
            'auditor' => 'Auditor',
            'karyawan' => 'Karyawan',
            default => 'Unknown',
        };
    }

    // Relationships
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}
