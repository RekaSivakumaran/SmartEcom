<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
      public function createPaymentIntent(Request $request)
      {
            Log::info('sending payment:', $request->session()->all());

            Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
                $paymentIntent = PaymentIntent::create([
                'amount'   => 1000, // £10.00 (in pence, not pounds)
                'currency' => 'gbp', // British Pounds
                'payment_method' => $request->payment_method_id,
                'confirm' => true,
                'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never',
                    ],
                ]);

            return response()->json(['success' => true, 'intent' => $paymentIntent]);

        } catch (\Exception $e) {
            Log::info('sending payment:' + $e, $request->session()->all());
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // // Stripe secret key set பண்ணுங்கள்
        // Stripe::setApiKey(env('STRIPE_SECRET'));

        // // Total amount from request (Rs)
        // $totalAmount = $request->input('total_amount', 500); // default 500 Rs
        // $amountInPence = $totalAmount * 100; // Stripe expects smallest currency unit

        // // Create PaymentIntent
        // $paymentIntent = PaymentIntent::create([
        //     'amount' => $amountInPence,
        //     'currency' => 'gbp', // UK pound
        //     'payment_method_types' => ['card'],
        // ]);

        // return response()->json([
        //     'clientSecret' => $paymentIntent->client_secret
        // ]);
    }

    // Step 2.2: Handle Order after successful payment
    public function handleOrder(Request $request)
    {
        // Collect all billing & shipping info
        $data = $request->all();

        // You can store in DB here
        // Example: Order::create([...])

        return redirect()->route('checkout.success')->with('success', 'Payment Successful & Order Placed!');
    }

     public function handleCOD(Request $request)
    {
        // COD process
        return "Order placed successfully (COD)";
    }
}
