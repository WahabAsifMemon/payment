<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;

class RefundController extends Controller
{
    public function createRefund(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $chargeId = $request->input('charge_id');
            $refund = \Stripe\Refund::create([
                'charge' => $chargeId,
                'payment_intent' => null,
            ]);

            return response()->json(['success' => true, 'message' => 'Refund created successfully']);
        } catch (ApiErrorException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

}
