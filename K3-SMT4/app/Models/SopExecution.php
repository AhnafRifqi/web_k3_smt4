<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SopExecution extends Model
{
    use HasFactory;

    protected $fillable = [
        'execution_date', 'employee_id', 'sop_id',
        'status', 'checklist', 'notes', 'photo_url', 'recorded_by',
    ];

    protected $casts = [
        'execution_date' => 'date',
        'checklist'      => 'array',
    ];

    public function employee() { return $this->belongsTo(Employee::class); }
    public function sop() { return $this->belongsTo(Sop::class); }
    public function recorder() { return $this->belongsTo(User::class, 'recorded_by'); }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'sesuai'          => 'Sesuai',
            'tidak_sesuai'    => 'Tidak Sesuai',
            'perlu_perbaikan' => 'Perlu Perbaikan',
            default           => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'sesuai'          => 'green',
            'tidak_sesuai'    => 'red',
            'perlu_perbaikan' => 'yellow',
            default           => 'gray',
        };
    }
}
