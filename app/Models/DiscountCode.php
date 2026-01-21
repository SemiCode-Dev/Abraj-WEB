<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percentage',
        'start_date',
        'end_date',
        'is_used',
        'used_at',
    ];

    protected $casts = [
        'discount_percentage' => 'float',
        'is_used' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'used_at' => 'datetime',
    ];

    /**
     * Check if the discount code is valid
     */
    public function isValid(): bool
    {
        $now = now();
        return !$this->is_used && 
               $now->gte($this->start_date) && 
               $now->lte($this->end_date);
    }
}
