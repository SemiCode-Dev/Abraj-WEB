<?php

namespace App\Helpers;

use App\Models\Setting;

class CommissionHelper
{
    /**
     * Get the commission percentage from settings
     */
    public static function getCommissionPercentage(): float
    {
        return (float) Setting::get('commission_percentage', 0);
    }

    /**
     * Calculate commission amount for a given price
     */
    public static function calculateCommissionAmount(float $price): float
    {
        $percentage = self::getCommissionPercentage();
        return round($price * ($percentage / 100), 2);
    }

    /**
     * Apply commission to a price and return the final price
     */
    public static function applyCommission(float $price): float
    {
        $commission = self::calculateCommissionAmount($price);
        return round($price + $commission, 2);
    }
}
