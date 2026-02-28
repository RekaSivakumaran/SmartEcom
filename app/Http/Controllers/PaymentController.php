<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session as StripeSession;
use App\Models\orderModel;
use App\Models\orderItemModel;
use App\Models\ProductModel;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

// public function createCheckoutSession(Request $request)
//     {
//         Stripe::setApiKey(env('STRIPE_SECRET'));

//         try {
//             // Calculate total from products
//             $total = 0;
//             $lineItems = [];

//             foreach ($request->products as $product) {
//                 $price    = (float) $product['price'];
//                 $quantity = (int)   $product['quantity'];
//                 $total   += $price * $quantity;

//                 $lineItems[] = [
//                     'price_data' => [
//                         'currency'     => 'usd',
//                         'product_data' => [
//                             'name' => $product['name'],
//                         ],
//                         // USD needs cents (× 100)
//                         'unit_amount'  => (int) round($price * 100),
//                     ],
//                     'quantity' => $quantity,
//                 ];
//             }

//             Log::info("Checkout Session Total: $" . ($total));

//             // ✅ Create Stripe Checkout Session
//             $session = StripeSession::create([
//                 'payment_method_types' => ['card'],
//                 'line_items'           => $lineItems,
//                 'mode'                 => 'payment',
//                 // After payment success → come back to our site
//                 'success_url'          => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
//                 // If user cancels → come back to checkout
//                 'cancel_url'           => route('payment.cancel'),
//             ]);

//             Log::info("Stripe Session Created: " . $session->id);

//             // ✅ Redirect user to Stripe's hosted card page
//             return redirect($session->url);

//         } catch (\Exception $e) {
//             Log::error("Stripe Session Error: " . $e->getMessage());
//             return back()->with('error', 'Payment error: ' . $e->getMessage());
//         }
//     }

public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Stripe requires line_items
            $lineItems = [];
            foreach ($request->products as $data) {
                $product = ProductModel::findOrFail($data['id']);
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'lkr',
                        'product_data' => ['name' => $product->name],
                        'unit_amount' => (int) round($product->price * 100),
                    ],
                    'quantity' => $data['quantity'],
                ];
            }

            // Create Stripe Checkout Session
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}&products=' . urlencode(json_encode($request->products)) . '&first_name=' . $request->first_name . '&last_name=' . $request->last_name . '&email=' . $request->email . '&phone=' . $request->phone . '&address1=' . $request->address1 . '&city=' . $request->city . '&country=' . $request->country . '&postcode=' . $request->postcode,
                'cancel_url'  => route('payment.cancel'),
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {
            return back()->with('error', 'Stripe Error: ' . $e->getMessage());
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

            if ($session->payment_status != 'paid') {
                return redirect()->route('home')->with('error', 'Payment not completed.');
            }

            // ✅ Only now store order
            $products = json_decode($request->products, true);

            $fakeRequest = new \Illuminate\Http\Request();
            $fakeRequest->replace(array_merge($request->all(), ['products' => $products]));

            $order = $this->storeOrder($fakeRequest, 'card');

            return redirect()->route('order.status', ['order' => $order->id])
                ->with('success', 'Order placed successfully (Card Payment)');

        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Payment verification failed.');
        }















        // Stripe::setApiKey(env('STRIPE_SECRET'));

        // try {
        //     $session = StripeSession::retrieve($request->session_id);

        //     // ✅ Save order to DB here:
        //     // Order::create([
        //     //     'user_id'           => auth()->id(),
        //     //     'total'             => $session->amount_total / 100,
        //     //     'payment_intent_id' => $session->payment_intent,
        //     //     'status'            => 'paid',
        //     // ]);

        //     return view('payment.success', ['session' => $session]);

        // } catch (\Exception $e) {
        //     return redirect('/')->with('error', 'Could not verify payment.');
        // }
    }

     
    // =========================================================
    // ✅ Cash on Delivery
    // =========================================================
    public function handleCOD(Request $request)
    {
        return redirect()->route('order.status')
            ->with('success', 'Order Placed Successfully (Cash On Delivery)');
    }

     private function storeOrder(Request $request, $paymentType = 'cash')
    {
        DB::beginTransaction();
        try {
            $totalPrice = 0;
            $orderItems = [];

            foreach ($request->products as $data) {
                $product = ProductModel::lockForUpdate()->findOrFail($data['id']);
                $quantity = $data['quantity'];
                if ($product->quantity < $quantity) {
                    DB::rollBack();
                    throw new \Exception("{$product->name} stock 0.");
                }

                $finalPrice = $product->price * $quantity;
                $totalPrice += $finalPrice;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity'   => $quantity,
                    'price'      => $product->price,
                    'total'      => $finalPrice,
                    'image_path' => $product->image ?? null,
                ];

                $product->quantity -= $quantity;
                $product->save();
            }

            // Bill Number
            $lastOrder = OrderModel::orderBy('id','desc')->first();
            $nextNumber = $lastOrder ? ((int) substr($lastOrder->bill_no, 2)) + 1 : 1;
            $billNo = 'TN' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $order = OrderModel::create([
                'bill_no' => $billNo,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'mobile_number' => $request->phone,
                'billing_address1' => $request->address1,
                'billing_city' => $request->city,
                'billing_country' => $request->country,
                'billing_postcode' => $request->postcode,
                'status' => 'Pending',
                'payment_status' => $paymentType === 'cash' ? 'Pending' : 'Paid',
                'total' => $totalPrice,
            ]);

            foreach ($orderItems as $item) {
                OrderItemModel::create(array_merge($item, ['order_id' => $order->id]));
            }

            DB::commit();
            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function orderStatus($order)
    {
        $order = OrderModel::find($order);
        if (!$order) return redirect()->route('home');
        return view('Client.orderstatus', compact('order'));
    }

    // ───────── Payment Cancel ─────────
    public function paymentCancel()
    {
        return redirect()->route('home')->with('error', 'Payment cancelled.');
    }

}
