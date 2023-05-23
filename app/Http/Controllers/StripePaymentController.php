<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use App\Models\Payment;

class StripePaymentController extends Controller
{
    public function stripePost(Request $request)
    {
        try {
            $stripe = new StripeClient(env('STRIPE_SECRET'));
            
            $res = $stripe->tokens->create([
                'card' => [
                    'number' => $request->number,
                    'exp_month' => $request->exp_month,
                    'exp_year' => $request->exp_year,
                    'cvc' => $request->cvc,
                ],
            ]);

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $response = \Stripe\Charge::create([
                'amount' => $request->amount,
                'currency' => 'usd',
                'source' => $res->id,
                'description' => $request->description,
            ]);

            $payment = Payment::create([
                'charge_id' => $response->id,
            ]);

            return response()->json([$response->status], 201);
        } catch (Exception $ex) {
            return response()->json([['response' => 'Error']], 500);
        }
    }
}
