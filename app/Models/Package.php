<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_ar',
        'description',
        'description_ar',
        'image',
        'price',
        'duration',
        'duration_ar',
        'details',
        'details_ar',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getLocaleTitleAttribute(): string
    {
        return app()->getLocale() == 'ar' ? $this->title_ar : $this->title;
    }

    public function getLocaleDescriptionAttribute(): ?string
    {
        return app()->getLocale() == 'ar' ? $this->description_ar : $this->description;
    }

    public function getLocaleDetailsAttribute(): ?string
    {
        return app()->getLocale() == 'ar' ? $this->details_ar : $this->details;
    }

    public function getLocaleDurationAttribute(): ?string
    {
        return app()->getLocale() == 'ar' ? $this->duration_ar : $this->duration;
    }
}
