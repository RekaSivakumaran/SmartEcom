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

/*edit */
.billing-summary {
    max-width: 400px;
    background: #fff;
    border: 1px solid #ddd;
    padding: 20px 25px;
    border-radius: 8px;
    font-family: Arial, sans-serif;
    color: #333;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    position: relative; /* for button positioning */
}

.billing-summary h3 {
    margin-bottom: 15px;
    font-size: 20px;
    border-bottom: 1px solid #eee;
    padding-bottom: 5px;
}

.billing-summary p {
    margin: 8px 0;
    line-height: 1.5;
}

.billing-summary a {
    color: #007bff;
    text-decoration: none;
}

.billing-summary a:hover {
    text-decoration: underline;
}

/* Edit Button */
.edit-btn {
    position: absolute;
    top: 20px;
    right: 20px;
    padding: 6px 12px;
    font-size: 14px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.edit-btn:hover {
    background-color: #0056b3;
}

</style>

<div class="all-title-box">
    <div class="container"><h2>Checkout</h2></div>
</div>

<div class="container my-5">



<div class="checkout-container">

<div class="billing-summary" id="billingDetails" style="{{ session('billing') ? '' : 'display:none;' }}">
    <h3>Billing</h3>
    <p><strong>Name:</strong> {{ session('billing.first_name') ?? '' }} {{ session('billing.last_name') ?? '' }}</p>
    <p><strong>Address:</strong> {{ session('billing.address1') ?? '' }}, {{ session('billing.city') ?? '' }}, {{ session('billing.postcode') ?? '' }}, {{ session('billing.country') ?? '' }}</p>
    <p><strong>Email:</strong> <a href="mailto:{{ session('billing.email') ?? '' }}">{{ session('billing.email') ?? '' }}</a></p>
    <p><strong>Phone:</strong> {{ session('billing.phone') ?? '' }}</p>
    <button type="button" onclick="editBilling()">Edit</button>
    <button type="submit" class="place-order" id="submit-btn">
                Place Order
    </button>
</div>

    




    {{-- ───── Billing Details ───── --}}
    <div class="billing" id="editDetails" style="{{ session('billing') ? 'display:none;' : 'display:block;' }}">
    <form method="POST" id="checkout-form" action="{{ route('billing.save') }}">
        @csrf

        @foreach($products as $product)
            <input type="hidden" name="products[{{ $loop->index }}][id]"       value="{{ $product['id'] }}">
            <input type="hidden" name="products[{{ $loop->index }}][name]"     value="{{ $product['name'] }}">
            <input type="hidden" name="products[{{ $loop->index }}][price]"    value="{{ $product['discountedPrice'] ?? $product['price'] }}">
            <input type="hidden" name="products[{{ $loop->index }}][quantity]" value="{{ $product['quantity'] }}">
        @endforeach

        <div>
            <label>First Name *</label>
            <input type="text" name="first_name" value="{{ old('first_name', session('billing.first_name')) }}">
        </div>

        <div>
            <label>Last Name *</label>
            <input type="text" name="last_name" value="{{ old('last_name', session('billing.last_name')) }}">
        </div>

        <div class="full-width">
            <label>Street Address 1 *</label>
            <input type="text" name="address1" value="{{ old('address1', session('billing.address1')) }}">
        </div>

        <div class="full-width">
            <label>Street Address 2</label>
            <input type="text" name="address2" value="{{ old('address2', session('billing.address2')) }}">
        </div>

        <div>
            <label>City *</label>
            <input type="text" name="city" value="{{ old('city', session('billing.city')) }}">
        </div>

        <div>
            <label>Country *</label>
            <input type="text" name="country" value="{{ old('country', session('billing.country')) }}">
        </div>

        <div>
            <label>Postcode / ZIP *</label>
            <input type="text" name="postcode" value="{{ old('postcode', session('billing.postcode')) }}">
        </div>

        <div>
            <label>Phone *</label>
            <input type="text" name="phone" value="{{ old('phone', session('billing.phone')) }}">
        </div>

        <div class="full-width">
            <label>Email *</label>
            <input type="email" name="email" value="{{ old('email', session('billing.email')) }}">
        </div>

        {{-- Checkbox: Ship Different --}}
        <div class="Checkfull-width">
            <input type="checkbox" name="ship_different" value="1" id="different-address" {{ session('shipping') ? 'checked' : '' }}>
            <span>Ship to a different address?</span>
        </div>

        {{-- Shipping Section --}}
        <div id="shippingDetails" style="{{ session('shipping') ? 'display:grid;' : 'display:none;' }}">
            <div class="full-width">
                <label>Shipping Address 1 *</label>
                <input type="text" name="ship_address1" value="{{ old('ship_address1', session('shipping.ship_address1')) }}">
            </div>
            <div class="full-width">
                <label>Shipping Address 2</label>
                <input type="text" name="ship_address2" value="{{ old('ship_address2', session('shipping.ship_address2')) }}">
            </div>
            <div>
                <label>Shipping City *</label>
                <input type="text" name="ship_city" value="{{ old('ship_city', session('shipping.ship_city')) }}">
            </div>
            <div>
                <label>Shipping Country *</label>
                <input type="text" name="ship_country" value="{{ old('ship_country', session('shipping.ship_country')) }}">
            </div>
            <div>
                <label>Shipping ZIP *</label>
                <input type="text" name="ship_zip" value="{{ old('ship_zip', session('shipping.ship_zip')) }}">
            </div>
            <div>
                <label>Shipping Phone *</label>
                <input type="text" name="ship_phone" value="{{ old('ship_phone', session('shipping.ship_phone')) }}">
            </div>
        </div>

        <button type="submit" class="place-order" id="submit-btn">Save Billing</button>
    </form>
</div>

    <div class="order-summary">
    <h2>Your order</h2>
    @php $total = 0; @endphp
    <table>
        <tr>
            <th>Product</th><th>Qty</th><th>Total</th>
        </tr>
        @foreach($products as $product)
            @php
                $quantity = $product['quantity'];
                $price = $product['discountedPrice'] ?? $product['price'];
                $subTotal = $price * $quantity;
                $total += $subTotal;
            @endphp
            <tr>
                <td>{{ $product['name'] }}</td>
                <td>{{ $quantity }}</td>
                <td>Rs. {{ number_format($subTotal,2) }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td>Total</td>
            <td></td>
            <td>Rs. {{ number_format($total,2) }}</td>
        </tr>
    </table>

    {{-- Payment method --}}
    <div class="payment-method">
        <label><input type="radio" name="payment" value="cash" checked> 💵 Cash on Delivery</label>
        <label><input type="radio" name="payment" value="card"> 💳 Card Payment</label>
    </div>

    <div id="stripe-info"><span>🔒</span>You will be redirected to <strong>Stripe</strong> secure payment page.</div>
</div>

 


    </div>
</div>
</div>

<script>
function editBilling() {
    const div1 = document.getElementById("billingDetails");
    const div2 = document.getElementById("editDetails");

    // Div1 visibility toggle
    if (div1.style.display === "none") {
        div1.style.display = "block";
    } else {
        div1.style.display = "none";
    }

    // Div2 visibility toggle
    if (div2.style.display === "none") {
        div2.style.display = "block";
    } else {
        div2.style.display = "none";
    }
}
</script>

<script>
window.onload = function() {
    const div1 = document.getElementById("billingDetails");
    const div2 = document.getElementById("editDetails");
    const btn  = document.getElementById("showBtn");

    div1.style.display = "none";
    div2.style.display = "none";

    btn.addEventListener("click", function() {
        div1.style.display = "block";
        div2.style.display = "block";
        btn.style.display = "none";  
    });
};
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const div1 = document.getElementById("billingDetails");
    const div2 = document.getElementById("editDetails");
    const checkbox = document.getElementById("different-address");
    const shippingDetails = document.getElementById("shippingDetails");

    // Shipping toggle
    checkbox?.addEventListener("change", function(){
        shippingDetails.style.display = this.checked ? "grid" : "none";
    });

    // Edit billing
    window.editBilling = function(){
        div1.style.display = div1.style.display === "none" ? "block" : "none";
        div2.style.display = div2.style.display === "none" ? "block" : "none";
    };

    // Payment method change
    const paymentRadios = document.querySelectorAll('input[name="payment"]');
    const stripeInfo = document.getElementById("stripe-info");
    const submitBtn = document.getElementById("submit-btn");
    const form = document.getElementById("checkout-form");

    paymentRadios.forEach(function(radio){
        radio.addEventListener("change", function(){
            if(this.value === "card"){
                stripeInfo.style.display = "block";
                submitBtn.textContent = "Proceed to Card Payment →";
                submitBtn.style.background = "#635bff";
            } else {
                stripeInfo.style.display = "none";
                submitBtn.textContent = "Save Billing";
                submitBtn.style.background = "#007bff";
            }
        });
    });

    // Form submit
    form?.addEventListener("submit", function(e){
        const paymentMethod = document.querySelector('input[name="payment"]:checked')?.value;
        if(paymentMethod === "card"){
            form.action = "{{ route('payment.checkout.session') }}";
            submitBtn.disabled = true;
            submitBtn.textContent = "Redirecting to Stripe...";
        }
    });
});
</script>


@endsection