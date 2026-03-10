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

public function createCheckoutSession1(Request $request)
{
    Stripe::setApiKey(env('STRIPE_SECRET'));

    // ✅ Request-லிருந்து நேரடியாக எடுங்கள் — session தேவையில்லை
    $products = $request->products;
    $billing  = [
        'first_name' => $request->first_name,
        'last_name'  => $request->last_name,
        'email'      => $request->email,
        'phone'      => $request->phone,
        'address1'   => $request->address1,
        'city'       => $request->city,
        'country'    => $request->country,
        'postcode'   => $request->postcode,
    ];

    if (!$products || count($products) == 0) {
        return redirect()->back()->with('error', 'No products selected.');
    }

    // ✅ Session-ல் save பண்ணுங்கள் — success page-ல் use ஆகும்
    session([
        'billing'        => $billing,
        'shipping'       => $request->ship_different ? [
            'ship_address1' => $request->ship_address1,
            'ship_address2' => $request->ship_address2,
            'ship_city'     => $request->ship_city,
            'ship_country'  => $request->ship_country,
            'ship_zip'      => $request->ship_zip,
            'ship_phone'    => $request->ship_phone,
        ] : null,
        'ship_different' => $request->ship_different ?? 0,
        'products'       => $products,
    ]);

    $lineItems = [];
    foreach ($products as $data) {
        $product = ProductModel::findOrFail($data['id']);

        $originalPrice = $product->price;
        $discount = 0;
        if ($product->discount_type === 'rate') {
            $discount = ($originalPrice * $product->discount_rate) / 100;
        } elseif ($product->discount_type === 'amount') {
            $discount = $product->discount_amount;
        }
        $discount        = min($discount, $originalPrice);
        $discountedPrice = $originalPrice - $discount;

        $lineItems[] = [
            'price_data' => [
                'currency'     => 'lkr',
                'product_data' => ['name' => $product->name],
                'unit_amount'  => (int) round($discountedPrice * 100),
            ],
            'quantity' => $data['quantity'],
        ];
    }

    try {
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'success_url'          => route('payment.success'),
            'cancel_url'           => route('payment.cancel'),
        ]);

        return redirect($session->url);

    } catch (\Exception $e) {
        return back()->with('error', 'Stripe Error: ' . $e->getMessage());
    }
}

// Step 1: Store checkout data in session and create Stripe session
public function createCheckoutSession(Request $request)
{
    Stripe::setApiKey(env('STRIPE_SECRET'));
    // Get billing & shipping from session
    $billing  = session('billing');
    $shipping = session('shipping');
    $products = session('products') ?? $request->products;

    if (!$billing || !$products) {
        return redirect()->back()->with('error','Billing or products missing.');
    }

     session([
        'products' => $products,
        'billing'  => $billing,
        'shipping' => $shipping,
       'ship_different' => session('ship_different') ?? 0,
        
    ]);

    // session([
    //     'checkout_products' => $products,
    //     'checkout_billing'  => $billing,
    //     'checkout_shipping' => $shipping,
    //     'checkout_ship_different' => session('ship_different') ?? 0,
    // ]);

    $lineItems = [];
    foreach ($products as $data) {
    $product = ProductModel::findOrFail($data['id']);

    // ✅ Blade-ல் போல் discount calculate பண்ணுங்கள்
    $originalPrice = $product->price;
    $discount = 0;

    if ($product->discount_type === 'rate') {
        $discount = ($originalPrice * $product->discount_rate) / 100;
    } elseif ($product->discount_type === 'amount') {
        $discount = $product->discount_amount;
    }

    $discount        = min($discount, $originalPrice);
    $discountedPrice = $originalPrice - $discount; // ✅ சரியான final price

    $lineItems[] = [
        'price_data' => [
            'currency'     => 'lkr',
            'product_data' => ['name' => $product->name],
            'unit_amount'  => (int) round($discountedPrice * 100), // ✅ discounted price
        ],
        'quantity' => $data['quantity'],
    ];
}

    try {
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('payment.success'),
            'cancel_url'  => route('payment.cancel'),
        ]);
        return redirect($session->url);
    } catch (\Exception $e) {
        return back()->with('error', 'Stripe Error: ' . $e->getMessage());
    }
}
// Step 2: Payment success → retrieve data from session
public function paymentSuccess(Request $request)
{
    try {
        // $products = session('checkout_products');
        // $billing  = session('checkout_billing');
        // $shipping = session('checkout_shipping');
        $shipDifferent = session('ship_different') ?? 0;

        $billing  = session('billing');
        $shipping = session('shipping');
        $products = session('products') ?? $request->products;

        if(!$products || !$billing) {
                        Log::info("Order data missing.");

            return redirect()->route('home')->with('error','Order data missing.');
        }

        $fakeRequest = new Request(array_merge([
            'products' => $products,
            'first_name'=> $billing['first_name'],
            'last_name' => $billing['last_name'],
            'email'     => $billing['email'],
            'phone'     => $billing['phone'],
            'address1'  => $billing['address1'],
            'city'      => $billing['city'],
            'country'   => $billing['country'],
            'postcode'  => $billing['postcode'],
            'ship_different' => $shipDifferent,
        ], $shipping ?? []));

        Log::info("Store Payment start: ", $fakeRequest->all()); // ✅ check data here

        $order = $this->storeOrder($fakeRequest, 'card');

        // Clear session
        session()->forget('products');
        session()->forget(['checkout_products','checkout_billing','checkout_shipping','checkout_ship_different']);

        return redirect()->route('order.status',['order'=>$order->id])
            ->with('success','Order placed successfully (Card Payment)');

    } catch (\Exception $e) {
        Log::error("Stripe Order store failed: ".$e->getMessage());
        return redirect()->route('home')->with('error','Payment verification failed.');
    }
}

// public function createCheckoutSession(Request $request)
//     {
//         Stripe::setApiKey(env('STRIPE_SECRET'));

//         try {
//             // Stripe requires line_items
//             $lineItems = [];
//             foreach ($request->products as $data) {
//                 $product = ProductModel::findOrFail($data['id']);
//                 $lineItems[] = [
//                     'price_data' => [
//                         'currency' => 'lkr',
//                         'product_data' => ['name' => $product->name],
//                         'unit_amount' => (int) round($product->price * 100),
//                     ],
//                     'quantity' => $data['quantity'],
//                 ];
//             }

//             // Create Stripe Checkout Session
//             $session = StripeSession::create([
//                 'payment_method_types' => ['card'],
//                 'line_items' => $lineItems,
//                 'mode' => 'payment',
//                 'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}&products=' . urlencode(json_encode($request->products)) . '&first_name=' . $request->first_name . '&last_name=' . $request->last_name . '&email=' . $request->email . '&phone=' . $request->phone . '&address1=' . $request->address1 . '&city=' . $request->city . '&country=' . $request->country . '&postcode=' . $request->postcode,
//                 'cancel_url'  => route('payment.cancel'),
//             ]);

//             return redirect($session->url);

//         } catch (\Exception $e) {
//             return back()->with('error', 'Stripe Error: ' . $e->getMessage());
//         }
//     }


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

// public function paymentSuccess(Request $request)
// {
// Log::info("Store Payment start: " . json_encode($request->all()));
//     Stripe::setApiKey(env('STRIPE_SECRET'));
//     try {
//         $sessionId = $request->session_id;
//         $session = StripeSession::retrieve($sessionId);

//         if($session->payment_status !== 'paid')
//             return redirect()->route('home')->with('error','Payment not completed.');

//         $products = session('checkout_products');
//         $billing  = session('checkout_billing');
//         $shipping = session('checkout_shipping');
//         $shipDifferent = session('checkout_ship_different');

//         if(!$products || !$billing)
//             return redirect()->route('home')->with('error','Order data missing.');

//         $fakeRequest = new Request(array_merge([
//             'products' => $products,
//             'first_name'=> $billing['first_name'],
//             'last_name' => $billing['last_name'],
//             'email'     => $billing['email'],
//             'phone'     => $billing['phone'],
//             'address1'  => $billing['address1'],
//             'city'      => $billing['city'],
//             'country'   => $billing['country'],
//             'postcode'  => $billing['postcode'],
//             'ship_different' => $shipDifferent,
//         ], $shipping ?? []));

//         $order = $this->storeOrder($fakeRequest, 'card');

//         session()->forget(['checkout_products','checkout_billing','checkout_shipping','checkout_ship_different']);

//         return redirect()->route('order.status',['order'=>$order->id])
//             ->with('success','Order placed successfully (Card Payment)');

//     } catch (\Exception $e) {
//         return redirect()->route('home')->with('error','Payment verification failed: '.$e->getMessage());
//     }
// }



    // public function paymentSuccess(Request $request)
    // {
    //      Stripe::setApiKey(env('STRIPE_SECRET'));

    //     try {
    //         $session = StripeSession::retrieve($request->session_id);

    //         if ($session->payment_status != 'paid') {
    //             return redirect()->route('home')->with('error', 'Payment not completed.');
    //         }

    //         // ✅ Only now store order
    //         $products = json_decode($request->products, true);

    //         $fakeRequest = new \Illuminate\Http\Request();
    //         $fakeRequest->replace(array_merge($request->all(), ['products' => $products]));

    //         $order = $this->storeOrder($fakeRequest, 'card');

    //         return redirect()->route('order.status', ['order' => $order->id])
    //             ->with('success', 'Order placed successfully (Card Payment)');

    //     } catch (\Exception $e) {
    //         return redirect()->route('home')->with('error', 'Payment verification failed.');
    //     }

    // }

     
    // =========================================================
    // ✅ Cash on Delivery
    // =========================================================

    // Cash on Delivery
public function handleCOD(Request $request)
{
    $order = $this->storeOrder($request,'cash');
    return redirect()->route('order.status',['order'=>$order->id])
        ->with('success','Order placed successfully (Cash On Delivery)');
}
    // public function handleCOD(Request $request)
    // {
    //     return redirect()->route('order.status')
    //         ->with('success', 'Order Placed Successfully (Cash On Delivery)');
    // }

    private function storeOrder(Request $request, $paymentType = 'cash')
    {
        Log::info("Store Payment start");

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
                'customer_id'      => session('client_id'),
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
