<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session as StripeSession;

class PaymentController extends Controller
{

public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Calculate total from products
            $total = 0;
            $lineItems = [];

            foreach ($request->products as $product) {
                $price    = (float) $product['price'];
                $quantity = (int)   $product['quantity'];
                $total   += $price * $quantity;

                $lineItems[] = [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => $product['name'],
                        ],
                        // USD needs cents (× 100)
                        'unit_amount'  => (int) round($price * 100),
                    ],
                    'quantity' => $quantity,
                ];
            }

            Log::info("Checkout Session Total: $" . ($total));

            // ✅ Create Stripe Checkout Session
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items'           => $lineItems,
                'mode'                 => 'payment',
                // After payment success → come back to our site
                'success_url'          => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                // If user cancels → come back to checkout
                'cancel_url'           => route('payment.cancel'),
            ]);

            Log::info("Stripe Session Created: " . $session->id);

            // ✅ Redirect user to Stripe's hosted card page
            return redirect($session->url);

        } catch (\Exception $e) {
            Log::error("Stripe Session Error: " . $e->getMessage());
            return back()->with('error', 'Payment error: ' . $e->getMessage());
        }
    }

   public function createPaymentIntent(Request $request)
    {
        Log::info("Stripe Payment Request:", $request->all());

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $total = 0;
            foreach ($request->products as $product) {
                $total += (float) $product['price'] * (int) $product['quantity'];
            }

            Log::info("PaymentIntent Total: Rs." . $total);

            if ($total < 100) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Minimum order amount is Rs. 100'
                ], 422);
            }

            $paymentIntent = PaymentIntent::create([
                'amount'   => (int) $total,
                'currency' => 'lkr',
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            return response()->json([
                'success'           => true,
                'client_secret'     => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ]);

        } catch (\Exception $e) {
            Log::error("Stripe Error: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // =========================================================
    // ✅ Payment Success Page
    // =========================================================
    public function paymentSuccess(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = StripeSession::retrieve($request->session_id);

            // ✅ Save order to DB here:
            // Order::create([
            //     'user_id'           => auth()->id(),
            //     'total'             => $session->amount_total / 100,
            //     'payment_intent_id' => $session->payment_intent,
            //     'status'            => 'paid',
            // ]);

            return view('payment.success', ['session' => $session]);

        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Could not verify payment.');
        }
    }

    // =========================================================
    // ✅ Payment Cancel Page
    // =========================================================
    public function paymentCancel()
    {
        return view('payment.cancel');
    }

    // =========================================================
    // ✅ Cash on Delivery
    // =========================================================
    public function handleCOD(Request $request)
    {
        return redirect()->route('order.status')
            ->with('success', 'Order Placed Successfully (Cash On Delivery)');
    }
}
