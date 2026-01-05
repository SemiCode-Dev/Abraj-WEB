<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarRentalBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone_country_code',
        'phone',
        'destination_country_id',
        'destination_city_id',
        'pickup_date',
        'pickup_time',
        'return_date',
        'return_time',
        'driver_option',
        'drivers',
        'notes',
        'status',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'return_date' => 'date',
        'drivers' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function destinationCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'destination_country_id');
    }

    public function destinationCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'destination_city_id');
    }
}
