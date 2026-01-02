<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'code',
        'nationality',
        'nationality_ar',
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function getLocaleNameAttribute(): string
    {
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name;
    }

    public function getLocaleNationalityAttribute(): ?string
    {
        $nationality = app()->getLocale() == 'ar' ? $this->nationality_ar : $this->nationality;
        return $nationality ?? '';
    }
}
