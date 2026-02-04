<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class CurrencyHelper
{
    /**
     * Get the current active currency
     */
    public static function getCurrentCurrency(): string
    {
        // For API, check header. For Web, check session.
        $request = request();
        
        if ($request->is('api/*') || $request->expectsJson()) {
            return strtoupper($request->header('Accept-Currency', 'USD'));
        }

        return Session::get('currency', 'USD');
    }

    /**
     * Get exchange rate for SAR (default 3.75)
     */
    public static function getExchangeRate(): float
    {
        return (float) Setting::get('usd_to_sar_rate', 3.75);
    }

    /**
     * Convert an amount from USD to the current currency
     */
    public static function convert(float $amount, ?string $targetCurrency = null): float
    {
        $currency = $targetCurrency ?? self::getCurrentCurrency();

        if ($currency === 'SAR') {
            return round($amount * self::getExchangeRate(), 2);
        }

        return round($amount, 2);
    }

    /**
     * Format an amount with its currency symbol (includes conversion by default)
     */
    public static function format(float $amount, ?string $currency = null, bool $shouldConvert = true): string
    {
        $currency = $currency ?? self::getCurrentCurrency();
        $amount = $shouldConvert ? self::convert($amount, $currency) : $amount;
        
        if ($currency === 'SAR') {
            return App::getLocale() === 'ar' ? number_format($amount, 2) . ' SAR' : 'SAR ' . number_format($amount, 2);
        }

        return App::getLocale() === 'ar' ? number_format($amount, 2) . ' USD' : 'USD ' . number_format($amount, 2);
    }

    /**
     * Get the currency symbol
     */
    public static function getSymbol(?string $currency = null): string
    {
        $currency = $currency ?? self::getCurrentCurrency();
        return $currency === 'SAR' ? (App::getLocale() === 'ar' ? 'ر.س' : 'SAR') : '$';
    }
}
