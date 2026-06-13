<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'name', 'description', 'steps', 'risks', 'controls',
        'apd_required', 'effective_date', 'file_url', 'category', 'status', 'created_by',
    ];

    protected $casts = [
        'steps'        => 'array',
        'risks'        => 'array',
        'controls'     => 'array',
        'apd_required' => 'array',
        'effective_date' => 'date',
    ];

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function executions() { return $this->hasMany(SopExecution::class); }

    public function getComplianceRateAttribute(): float
    {
        $total = $this->executions()->count();
        if ($total === 0) return 0;
        $sesuai = $this->executions()->where('status', 'sesuai')->count();
        return round(($sesuai / $total) * 100, 1);
    }
}
