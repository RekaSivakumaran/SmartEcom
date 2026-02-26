@extends('Layout.client')

@section('content')

<style>
.checkout-container {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}
.billing {
    flex: 2;
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
.order-summary {
    flex: 1;
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
.billing form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px 20px;
}
.billing form label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}
.billing form input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
.full-width { grid-column: span 2; }
#shippingDetails {
    grid-column: span 2;
    display: none;
    grid-template-columns: 1fr 1fr;
    gap: 15px 20px;
}
#shippingDetails .full-width { grid-column: span 2; }
.Checkfull-width {
    grid-column: span 2;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: bold;
    cursor: pointer;
    padding: 10px 0;
    width: 100%;
}
.Checkfull-width input[type="checkbox"] {
    width: 18px; height: 18px;
    margin: 0;
    accent-color: green;
    cursor: pointer;
    flex-shrink: 0;
}
.Checkfull-width span {
    user-select: none;
    white-space: nowrap;
    font-size: 18px;
    font-weight: bold;
    color: #333;
}
input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
.order-summary table { width: 100%; border-collapse: collapse; }
.order-summary table th,
.order-summary table td { text-align: left; padding: 10px 50px 0 0; }
.order-summary table tr:not(:last-child) td { border-bottom: 1px solid #eee; }
.total-row { font-weight: bold; font-size: 1.2em; }
.payment-method { margin-top: 20px; }
.payment-method label { display: block; margin-bottom: 10px; cursor: pointer; }
.place-order {
    margin-top: 20px;
    padding: 12px 20px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
}
.place-order:hover { background: #0056b3; }

/* Stripe info box */
#stripe-info {
    display: none;
    margin-top: 12px;
    padding: 12px 16px;
    background: #f0f4ff;
    border: 1px solid #c7d2fe;
    border-radius: 8px;
    font-size: 14px;
    color: #3730a3;
}
#stripe-info span { font-size: 20px; margin-right: 6px; }

@media(max-width: 768px){
    .checkout-container { flex-direction: column; }
}
</style>

<div class="all-title-box">
    <div class="container"><h2>Checkout</h2></div>
</div>

<div class="container my-5">
<div class="checkout-container">

    {{-- ───── Billing Details ───── --}}
    <div class="billing">
        <h2>Billing Details</h2>

        {{-- 
            IMPORTANT: form action changes based on payment method.
            COD  → route('order.single')              (normal POST)
            Card → route('payment.checkout.session')  (Stripe redirect)
        --}}
        <form method="POST" id="checkout-form" action="{{ route('order.single') }}">
            @csrf

            @foreach($products as $product)
                <input type="hidden" name="products[{{ $loop->index }}][id]"       value="{{ $product['id'] }}">
                <input type="hidden" name="products[{{ $loop->index }}][name]"     value="{{ $product['name'] }}">
                <input type="hidden" name="products[{{ $loop->index }}][price]"    value="{{ $product['discountedPrice'] ?? $product['price'] }}">
                <input type="hidden" name="products[{{ $loop->index }}][quantity]" value="{{ $product['quantity'] }}">
            @endforeach

            <div>
                <label>First Name *</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}">
                @error('first_name')<small style="color:red">{{ $message }}</small>@enderror
            </div>

            <div>
                <label>Last Name *</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}">
                @error('last_name')<small style="color:red">{{ $message }}</small>@enderror
            </div>

            <div class="full-width">
                <label>Street Address 1 *</label>
                <input type="text" name="address1" value="{{ old('address1') }}">
                @error('address1')<small style="color:red">{{ $message }}</small>@enderror
            </div>

            <div class="full-width">
                <label>Street Address 2</label>
                <input type="text" name="address2" value="{{ old('address2') }}">
            </div>

            <div>
                <label>Town / City *</label>
                <input type="text" name="city" value="{{ old('city') }}">
                @error('city')<small style="color:red">{{ $message }}</small>@enderror
            </div>

            <div>
                <label>Country *</label>
                <input type="text" name="country" value="{{ old('country') }}">
                @error('country')<small style="color:red">{{ $message }}</small>@enderror
            </div>

            <div>
                <label>Postcode / ZIP *</label>
                <input type="text" name="postcode" value="{{ old('postcode') }}">
                @error('postcode')<small style="color:red">{{ $message }}</small>@enderror
            </div>

            <div>
                <label>Phone *</label>
                <input type="text" name="phone" value="{{ old('phone') }}">
                @error('phone')<small style="color:red">{{ $message }}</small>@enderror
            </div>

            <div class="full-width">
                <label>Email *</label>
                <input type="email" name="email" value="{{ old('email') }}">
                @error('email')<small style="color:red">{{ $message }}</small>@enderror
            </div>

            {{-- Checkbox --}}
            <div class="Checkfull-width">
                <input type="checkbox" name="ship_different" value="1" id="different-address"
                    {{ old('ship_different') ? 'checked' : '' }}>
                <span>Ship to a different address?</span>
            </div>

            {{-- Shipping Section --}}
            <div id="shippingDetails" style="display: {{ old('ship_different') ? 'grid' : 'none' }};">
                <div class="full-width">
                    <label>Shipping Address 1 *</label>
                    <input type="text" name="ship_address1" value="{{ old('ship_address1') }}">
                    @error('ship_address1')<small style="color:red">{{ $message }}</small>@enderror
                </div>
                <div class="full-width">
                    <label>Shipping Address 2</label>
                    <input type="text" name="ship_address2" value="{{ old('ship_address2') }}">
                </div>
                <div>
                    <label>Shipping City *</label>
                    <input type="text" name="ship_city" value="{{ old('ship_city') }}">
                    @error('ship_city')<small style="color:red">{{ $message }}</small>@enderror
                </div>
                <div>
                    <label>Shipping Country *</label>
                    <input type="text" name="ship_country" value="{{ old('ship_country') }}">
                    @error('ship_country')<small style="color:red">{{ $message }}</small>@enderror
                </div>
                <div>
                    <label>Shipping ZIP *</label>
                    <input type="text" name="ship_zip" value="{{ old('ship_zip') }}">
                    @error('ship_zip')<small style="color:red">{{ $message }}</small>@enderror
                </div>
                <div>
                    <label>Shipping Phone *</label>
                    <input type="text" name="ship_phone" value="{{ old('ship_phone') }}">
                    @error('ship_phone')<small style="color:red">{{ $message }}</small>@enderror
                </div>
            </div>

            <button type="submit" class="place-order" id="submit-btn">
                Place Order
            </button>

        </form>
    </div>

    {{-- ───── Order Summary ───── --}}
    <div class="order-summary">
        <h2>Your order</h2>

        @php $total = 0; @endphp
        <table>
            <tr>
                <th>Products</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>

            @foreach($products as $product)
                @php
                    $quantity      = $product['quantity'] ?? 1;
                    $originalPrice = $product['price'];

                    if ($product['discount_type'] == 'rate') {
                        $discountValue = ($originalPrice * $product['discount_rate']) / 100;
                    } elseif ($product['discount_type'] == 'amount') {
                        $discountValue = $product['discount_amount'];
                    } else {
                        $discountValue = 0;
                    }

                    $discountValue   = min($discountValue, $originalPrice);
                    $discountedPrice = $originalPrice - $discountValue;
                    $subTotal        = $discountedPrice * $quantity;
                    $total          += $subTotal;
                @endphp

                <tr>
                    <td>{{ $product['name'] }}</td>
                    <td>{{ $quantity }}</td>
                    <td>Rs. {{ number_format($subTotal, 2) }}</td>
                </tr>

                @if($discountValue > 0)
                <tr>
                    <td>Discount</td>
                    <td></td>
                    <td style="color:green;">- Rs. {{ number_format($discountValue * $quantity, 2) }}</td>
                </tr>
                @endif
            @endforeach

            <tr>
                <td>Shipping:</td>
                <td></td>
                <td>Free Shipping</td>
            </tr>
            <tr class="total-row">
                <td>Total</td>
                <td></td>
                <td>Rs. {{ number_format($total, 2) }}</td>
            </tr>
        </table>

        {{-- Payment Method --}}
        <div class="payment-method">
            <label>
                <input type="radio" name="payment" value="cash" checked>
                💵 Cash on Delivery
            </label>
            <label>
                <input type="radio" name="payment" value="card">
                💳 Card Payment (Stripe)
            </label>
        </div>

        {{-- Info box shown when Card is selected --}}
        <div id="stripe-info">
            <span>🔒</span>
            You will be redirected to <strong>Stripe's secure payment page</strong> to enter your card details.
        </div>

    </div>
</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ── Shipping toggle ──────────────────────────────────────────
    const checkbox       = document.getElementById("different-address");
    const shippingDetails = document.getElementById("shippingDetails");

    checkbox.addEventListener("change", function () {
        shippingDetails.style.display = this.checked ? "grid" : "none";
    });

    // ── Payment method change ────────────────────────────────────
    const paymentRadios = document.querySelectorAll('input[name="payment"]');
    const stripeInfo    = document.getElementById("stripe-info");
    const form          = document.getElementById("checkout-form");
    const submitBtn     = document.getElementById("submit-btn");

    paymentRadios.forEach(function (radio) {
        radio.addEventListener("change", function () {
            if (this.value === "card") {
                stripeInfo.style.display  = "block";
                submitBtn.textContent     = "Proceed to Card Payment →";
                submitBtn.style.background = "#635bff"; // Stripe purple
            } else {
                stripeInfo.style.display  = "none";
                submitBtn.textContent     = "Place Order";
                submitBtn.style.background = "#007bff";
            }
        });
    });

    // ── Form submit ──────────────────────────────────────────────
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const paymentMethod = document.querySelector('input[name="payment"]:checked').value;

        if (paymentMethod === "cash") {
            // Normal COD submit
            form.action = "{{ route('order.single') }}";
            form.submit();

        } else if (paymentMethod === "card") {
            // ✅ Change form action to Stripe checkout session route
            form.action = "{{ route('payment.checkout.session') }}";
            submitBtn.disabled    = true;
            submitBtn.textContent = "Redirecting to Stripe...";
            form.submit(); // POST to backend → backend redirects to Stripe page
        }
    });

});
</script>

@endsection