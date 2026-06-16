<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active', 'avatar_url', 'is_validated',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_validated' => 'boolean',
        ];
    }

    // Role Helpers
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isSupervisorK3(): bool { return $this->role === 'supervisor_k3'; }
    public function isAuditor(): bool { return $this->role === 'auditor'; }
    public function isKaryawan(): bool { return $this->role === 'karyawan'; }
    public function isPending(): bool { return $this->role === 'pending'; }
    public function isValidated(): bool { return $this->is_validated === true && $this->role !== 'pending'; }
    public function canManage(): bool { return in_array($this->role, ['admin', 'supervisor_k3']); }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'supervisor_k3' => 'Supervisor K3',
            'auditor' => 'Auditor',
            'karyawan' => 'Karyawan',
            'pending' => 'Pending',
            default => 'Unknown',
        };
    }

    /**
     * Cek apakah user adalah admin yang tidak bisa dimodifikasi
     */
    public function isImmutableAdmin(): bool
    {
        return $this->role === 'admin' && $this->id === 1;
    }

    // Relationships
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}
