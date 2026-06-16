<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'form_id', 'field_type', 'label', 'options', 'is_required', 'order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(MonitoringForm::class, 'form_id');
    }

    public function getFieldTypeLabelAttribute(): string
    {
        return match ($this->field_type) {
            'text' => 'Teks',
            'number' => 'Angka',
            'yes_no' => 'Ya/Tidak',
            'checklist' => 'Checklist',
            'dropdown' => 'Dropdown',
            'date' => 'Tanggal',
            'photo' => 'Foto',
            'signature' => 'Tanda Tangan',
            'rating' => 'Rating',
            default => $this->field_type,
        };
    }
}
