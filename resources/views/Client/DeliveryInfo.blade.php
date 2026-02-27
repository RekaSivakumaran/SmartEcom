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

.full-width {
    grid-column: span 2;
}

#shippingDetails {
    grid-column: span 2;
    display: none;
    grid-template-columns: 1fr 1fr;
    gap: 15px 20px;
}

#shippingDetails .full-width {
    grid-column: span 2;
}

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
  width: 18px;
  height: 18px;
  margin: 0;           /* Removes default browser margins */
  accent-color: green;
  cursor: pointer;
  flex-shrink: 0;       
}

.Checkfull-width span {
  user-select: none;
  white-space: nowrap; /* Keeps 'Ship to a different address?' on one line */
  font-size: 18px;
  font-weight: bold;
  color: #333;
  
}

input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}


 .order-summary table {
    width: 100%;
    border-collapse: collapse;
  }

  .order-summary table th, .order-summary table td {
    text-align: left;
    padding: 10px 50px 0 0;
  }

  .order-summary table tr:not(:last-child) td {
    border-bottom: 1px solid #eee;
  }

  .total-row {
    font-weight: bold;
    font-size: 1.2em;
  }

  .payment-method {
    margin-top: 20px;
  }

  .payment-method label {
    display: block;
    margin-bottom: 10px;
  }

  .place-order {
    margin-top: 20px;
    padding: 12px 20px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  .place-order:hover {
    background: #0056b3;
  }
 

@media(max-width: 768px){
    .checkout-container {
        flex-direction: column;
    }
}

</style>

<div class="all-title-box">
    <div class="container">
        <h2>Checkout</h2>
    </div>
</div>

<div class="container my-5">
<div class="checkout-container">

    <!-- Billing Details -->
    <div class="billing">
        <h2>Billing Details</h2>

        <!-- <form method="POST" action="{{ route('order.single') }}"> -->
            <form method="POST" id="checkout-form">
    @csrf

    {{-- SHOW ALL ERRORS --}}
    <!-- @if ($errors->any())
        <div style="color:red; margin-bottom:15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif -->

    @foreach($products as $product)
        <input type="hidden" name="products[{{ $product['id'] }}][id]" value="{{ $product['id'] }}">
        <input type="hidden" name="products[{{ $product['id'] }}][quantity]" value="{{ $product['quantity'] }}">
    @endforeach
    <div>
        <label>First Name *</label>
        <input type="text" 
               name="first_name" 
               value="{{ old('first_name') }}">
        @error('first_name')
            <small style="color:red">{{ $message }}</small>
        @enderror
    </div>


    <div>
        <label>Last Name *</label>
        <input type="text" 
               name="last_name" 
               value="{{ old('last_name') }}">
        @error('last_name')
            <small style="color:red">{{ $message }}</small>
        @enderror
    </div>


    <div class="full-width">
        <label>Street Address 1 *</label>
        <input type="text" 
               name="address1" 
               value="{{ old('address1') }}">
        @error('address1')
            <small style="color:red">{{ $message }}</small>
        @enderror
    </div>


    <div class="full-width">
        <label>Street Address 2</label>
        <input type="text" 
               name="address2" 
               value="{{ old('address2') }}">
    </div>


    <div>
        <label>Town / City *</label>
        <input type="text" 
               name="city" 
               value="{{ old('city') }}">
        @error('city')
            <small style="color:red">{{ $message }}</small>
        @enderror
    </div>


    <div>
        <label>Country *</label>
        <input type="text" 
               name="country" 
               value="{{ old('country') }}">
        @error('country')
            <small style="color:red">{{ $message }}</small>
        @enderror
    </div>


    <div>
        <label>Postcode / ZIP *</label>
        <input type="text" 
               name="postcode" 
               value="{{ old('postcode') }}">
        @error('postcode')
            <small style="color:red">{{ $message }}</small>
        @enderror
    </div>


    <div>
        <label>Phone *</label>
        <input type="text" 
               name="phone" 
               value="{{ old('phone') }}">
        @error('phone')
            <small style="color:red">{{ $message }}</small>
        @enderror
    </div>


    <div class="full-width">
        <label>Email *</label>
        <input type="email" 
               name="email" 
               value="{{ old('email') }}">
        @error('email')
            <small style="color:red">{{ $message }}</small>
        @enderror
    </div>


    {{-- Checkbox --}}
    <div class="Checkfull-width">
        <input type="checkbox" 
               name="ship_different"
               value="1"
               id="different-address"
               {{ old('ship_different') ? 'checked' : '' }}>
        <span>Ship to a different address?</span>
    </div>


    {{-- Shipping Section --}}
    <div id="shippingDetails"
         style="display: {{ old('ship_different') ? 'block' : 'none' }};">

        <div class="full-width">
            <label>Shipping Address 1 *</label>
            <input type="text" 
                   name="ship_address1" 
                   value="{{ old('ship_address1') }}">
            @error('ship_address1')
                <small style="color:red">{{ $message }}</small>
            @enderror
        </div>

        <div class="full-width">
            <label>Shipping Address 2</label>
            <input type="text" 
                   name="ship_address2" 
                   value="{{ old('ship_address2') }}">
        </div>

        <div>
            <label>Shipping City *</label>
            <input type="text" 
                   name="ship_city" 
                   value="{{ old('ship_city') }}">
            @error('ship_city')
                <small style="color:red">{{ $message }}</small>
            @enderror
        </div>

        <div>
            <label>Shipping Country *</label>
            <input type="text" 
                   name="ship_country" 
                   value="{{ old('ship_country') }}">
            @error('ship_country')
                <small style="color:red">{{ $message }}</small>
            @enderror
        </div>

        <div>
            <label>Shipping ZIP *</label>
            <input type="text" 
                   name="ship_zip" 
                   value="{{ old('ship_zip') }}">
            @error('ship_zip')
                <small style="color:red">{{ $message }}</small>
            @enderror
        </div>

        <div>
            <label>Shipping Phone *</label>
            <input type="text" 
                   name="ship_phone" 
                   value="{{ old('ship_phone') }}">
            @error('ship_phone')
                <small style="color:red">{{ $message }}</small>
            @enderror
        </div>

    </div>
<button class="place-order">
    Place Order
</button>

    <!-- <button type="submit">Place Order</button> -->

</form>

    </div>

    <!-- Order Summary -->
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
                    $quantity = $product['quantity'] ?? 1;
                    $originalPrice = $product['price'];

                    if ($product['discount_type'] == 'rate') {
                        $discountValue = ($originalPrice * $product['discount_rate']) / 100;
                    } elseif ($product['discount_type'] == 'amount') {
                        $discountValue = $product['discount_amount'];
                    } else {
                        $discountValue = 0;
                    }

                    $discountValue = min($discountValue, $originalPrice);
                    $discountedPrice = $originalPrice - $discountValue;
                    $subTotal = $discountedPrice * $quantity;
                    $total += $subTotal;
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
                <td>Shopping: </td>
                <td></td>
                <td>Free Shipping</td>
            </tr>

            <tr class="total-row">
                <td>Total</td>
                <td></td>
                <td>Rs. {{ number_format($total, 2) }}</td>
            </tr>
        </table>

        <div class="payment-method">
            <label>
                <input type="radio" name="payment" value="cash" checked>
                Cash on Delivery
            </label>
            <label>
                <input type="radio" name="payment" value="card">
                Card payment
            </label>
        </div>

          
   
<!-- <button class="place-order">
    Place Order
</button> -->

</div>


</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const checkbox = document.getElementById("different-address");
    const shippingDetails = document.getElementById("shippingDetails");

    shippingDetails.style.display = "none";

    checkbox.addEventListener("change", function() {
        shippingDetails.style.display = this.checked ? "grid" : "none";
    });

});
</script>

<script src="https://js.stripe.com/v3/"></script>
<!-- <script>
document.addEventListener("DOMContentLoaded", function() {

    const payButton = document.querySelector('button.place-order');

    payButton.addEventListener("click", async function(e) {
        e.preventDefault();

        // Payment method check
        const paymentMethod = document.querySelector('input[name="payment"]:checked').value;
        if(paymentMethod !== 'opayo'){
            // COD selected
            document.querySelector('form').submit();
            return;
        }

        // Total amount from Blade variable
        const totalAmount = {{ $total }};
        const csrfToken = '{{ csrf_token() }}';

        // Step 4.1: Create PaymentIntent
        const response = await fetch('{{ route("stripe.payment-intent") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ total_amount: totalAmount })
        });

        const data = await response.json();
        const clientSecret = data.clientSecret;

        // Step 4.2: Stripe.js test payment
        const stripe = Stripe('{{ env("STRIPE_KEY") }}');

        const result = await stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: {
                    number: '4242424242424242', // Test card
                    exp_month: 12,
                    exp_year: 2034,
                    cvc: '123'
                }
            }
        });

        if(result.error){
            alert('Payment Failed: ' + result.error.message);
        } else if(result.paymentIntent.status === 'succeeded'){
            alert('Payment Success!');

            // Submit the form to handle order
            document.querySelector('form').action = '{{ route("order.success") }}';
            document.querySelector('form').submit();
        }
    });

});
</script> -->

<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const stripe = Stripe('{{ env("STRIPE_KEY") }}'); // publishable key
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const form = document.getElementById('checkout-form');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const paymentMethod = document.querySelector('input[name="payment"]:checked').value;

        // COD selected
        if(paymentMethod === 'cash'){
            form.action = '{{ route("order.single") }}';
            form.submit();
            return;
        }
        if(paymentType === 'card') {
        const res = await fetch('{{ route("stripe.session") }}', {
            method:'POST',
            headers:{ 
                'Content-Type':'application/json', 
                'X-CSRF-TOKEN':'{{ csrf_token() }}' 
            },
            body: JSON.stringify({ products })
        });
        const data = await res.json();
        const stripe = Stripe('{{ env("STRIPE_KEY") }}');
        stripe.redirectToCheckout({ sessionId: data.id });
    }

        // // Stripe payment
        // const totalAmount = 500; // change to dynamic total
        // const csrfToken = '{{ csrf_token() }}';

        // // Create PaymentIntent via backend
        // const response = await fetch('{{ route("stripe.payment-intent") }}', {
        //     method: 'POST',
        //     headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        //     body: JSON.stringify({ total_amount: totalAmount })
        // });

        // const data = await response.json();

        // const result = await stripe.confirmCardPayment(data.clientSecret, {
        //     payment_method: { card: card }
        // });

        // if(result.error){
        //     document.getElementById('card-errors').textContent = result.error.message;
        // } else if(result.paymentIntent.status === 'succeeded'){
        //     alert('Payment Successful!');
        //     form.action = '{{ route("order.success") }}';
        //     form.submit();
        // }
    });
});
</script>

@endsection


 





