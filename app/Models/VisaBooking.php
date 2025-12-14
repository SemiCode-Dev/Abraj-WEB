<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisaBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone_country_code',
        'phone',
        'visa_type',
        'country_id',
        'duration',
        'passport_number',
        'comment',
        'status',
    ];

    protected $casts = [
        'duration' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
