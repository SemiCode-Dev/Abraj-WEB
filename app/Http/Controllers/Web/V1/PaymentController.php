<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function apsPayment()
    {

        return $this->paymentService->apsPayment();
    }

    public function apsCallback(Request $request)
    {
        // info('entered callback');
        // info($request);
        set_time_limit(180);
        $data = $request->all();

        return $this->paymentService->apsCallback($data);
    }
}
