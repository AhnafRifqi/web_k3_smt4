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

    // ============================================================
    // NEW ROLE HELPERS (GAP 1)
    // ============================================================
    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isK3Manager(): bool { return $this->role === 'k3_manager'; }
    public function isK3Officer(): bool { return $this->role === 'k3_officer'; }
    public function isDeptHead(): bool { return $this->role === 'dept_head'; }
    public function isEmployee(): bool { return $this->role === 'employee'; }
    public function isAuditor(): bool { return $this->role === 'auditor'; }
    public function isViewer(): bool { return $this->role === 'viewer'; }

    // Legacy aliases for backward compatibility
    public function isAdmin(): bool { return $this->isSuperAdmin(); }
    public function isSupervisorK3(): bool { return $this->isK3Manager(); }
    public function isKaryawan(): bool { return $this->isEmployee(); }
    public function isPending(): bool { return false; } // 'pending' no longer exists

    public function isValidated(): bool { return $this->is_validated === true && $this->role !== 'viewer'; }

    /**
     * Users who can manage K3 operational modules
     */
    public function canManage(): bool
    {
        return in_array($this->role, ['super_admin', 'k3_manager', 'k3_officer']);
    }

    /**
     * Users who can approve documents
     */
    public function canApprove(): bool
    {
        return in_array($this->role, ['super_admin', 'k3_manager']);
    }

    /**
     * Users with read-only access to everything
     */
    public function isReadOnly(): bool
    {
        return in_array($this->role, ['auditor', 'viewer']);
    }

    /**
     * Users who can create/edit/delete content
     */
    public function canWrite(): bool
    {
        return in_array($this->role, ['super_admin', 'k3_manager', 'k3_officer', 'dept_head', 'employee']);
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'System Administrator',
            'k3_manager'  => 'K3 Manager',
            'k3_officer'  => 'K3 Officer',
            'dept_head'   => 'Department Head',
            'employee'    => 'Employee / Inspector',
            'auditor'     => 'Auditor (Internal/External)',
            'viewer'      => 'Viewer',
            default       => 'Unknown',
        };
    }

    public function getRoleColorAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'red',
            'k3_manager'  => 'blue',
            'k3_officer'  => 'indigo',
            'dept_head'   => 'purple',
            'employee'    => 'green',
            'auditor'     => 'orange',
            'viewer'      => 'gray',
            default       => 'gray',
        };
    }

    /**
     * Cek apakah user adalah admin yang tidak bisa dimodifikasi
     */
    public function isImmutableAdmin(): bool
    {
        return $this->role === 'super_admin' && $this->id === 1;
    }

    // Relationships
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}