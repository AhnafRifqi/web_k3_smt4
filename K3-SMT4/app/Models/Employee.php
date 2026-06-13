<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nik', 'name', 'position', 'department_id', 'email',
        'phone', 'join_date', 'status', 'photo_url', 'user_id',
    ];

    protected $casts = ['join_date' => 'date'];

    public function department() { return $this->belongsTo(Department::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function sopExecutions() { return $this->hasMany(SopExecution::class); }
    public function capas() { return $this->hasMany(Capa::class, 'pic_id'); }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif',
            'cuti' => 'Cuti',
            'resign' => 'Resign',
            default => '-',
        };
    }
}
