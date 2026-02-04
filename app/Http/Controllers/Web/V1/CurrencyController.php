<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CurrencyController extends Controller
{
    /**
     * Switch the current currency
     */
    public function switchCurrency(string $currency)
    {
        $currency = strtoupper($currency);
        
        if (in_array($currency, ['USD', 'SAR'])) {
            Session::put('currency', $currency);
        }

        return redirect()->back();
    }
}
