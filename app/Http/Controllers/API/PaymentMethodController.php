<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    //
    public function getPaymentMethods()
    {
        try {
            $payment_methods = PaymentMethod::get();

            //return successful response
            return response()->json(['error' => false, 'result' => $payment_methods], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }
}
