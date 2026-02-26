<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UploadedFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversion_task_id',
        'user_id',
        'visitor_token',
        'original_name',
        'mime_type',
        'size_bytes',
        'disk',
        'path',
        'sort_order',
        'rotation_degrees',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function conversionTask(): BelongsTo
    {
        return $this->belongsTo(ConversionTask::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
