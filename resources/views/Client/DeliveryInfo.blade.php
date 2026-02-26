@extends('Layout.client')

@section('content')

 <div class="all-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Checkout</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Shop</a></li>
                        <li class="breadcrumb-item active">Checkout </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

 <div class="checkout-container">
  <!-- Billing Details -->
  <div class="billing">
    <h2>Billing Details</h2>
    <form>
      <div>
        <label>First Name *</label>
        <input type="text" value="sinthuja">
      </div>
      <div>
        <label>Last Name *</label>
        <input type="text" value="vijayajanan">
      </div>
      <div class="full-width">
        <label>Street Address 1 *</label>
        <input type="text" value="canada">
      </div>
      <div class="full-width">
        <label>Street Address 2</label>
        <input type="text" value="jndciuhwi">
      </div>
      <div>
        <label>Town / City *</label>
        <input type="text" value="dclehf">
      </div>
      <div>
        <label>Country *</label>
        <input type="text" value="siduhchc">
      </div>
      <div>
        <label>Postcode / ZIP *</label>
        <input type="text" value="iudhfi">
      </div>
      <div>
        <label>Phone *</label>
        <input type="text" value="5154666855">
      </div>
      <div class="full-width">
        <label>Email address *</label>
        <input type="email" value="sinthuja@gmail.com">
      </div>
      <div class="Checkfull-width">
  <input type="checkbox" id="different-address">
  <span>Ship to a different address?</span>
</div>

 <div>
        <label>First Name *</label>
        <input type="text" value="sinthuja">
      </div>
      <div>
        <label>Last Name *</label>
        <input type="text" value="vijayajanan">
      </div>
      <div class="full-width">
        <label>Street Address 1 *</label>
        <input type="text" value="canada">
      </div>
      <div class="full-width">
        <label>Street Address 2</label>
        <input type="text" value="jndciuhwi">
      </div>
      <div>
        <label>Town / City *</label>
        <input type="text" value="dclehf">
      </div>
      <div>
        <label>Country *</label>
        <input type="text" value="siduhchc">
      </div>
      <div>
        <label>Postcode / ZIP *</label>
        <input type="text" value="iudhfi">
      </div>
      <div>
        <label>Phone *</label>
        <input type="text" value="5154666855">
      </div>
    </form>
  </div>

  <!-- Order Summary -->
  <div class="order-summary">
    <h2>Your Order</h2>
    <table>
      <tr>
        <th>Product</th>
        <th>Total</th>
      </tr>
      <tr>
        <td>bag1</td>
        <td>£8,000.00</td>
      </tr>
      <tr>
        <td>bag</td>
        <td>£1,500.00</td>
      </tr>
      <tr>
        <td>glass</td>
        <td>£6,000.00</td>
      </tr>
      <tr>
        <td>bag</td>
        <td>£2,000.00</td>
      </tr>
      <tr class="total-row">
        <td>Subtotal:</td>
        <td>£41,500.00</td>
      </tr>
      <tr>
        <td>Shipping:</td>
        <td>Free Shipping</td>
      </tr>
      <tr class="total-row">
        <td>Total:</td>
        <td>£41,500.00</td>
      </tr>
    </table>

    <div class="payment-method">
      <label><input type="radio" name="payment" checked> Cash on delivery</label>
      <label><input type="radio" name="payment"> Opayo</label>
    </div>

    <button class="place-order">Place Order</button>
  </div>
</div>
<style>
 .Checkfull-width {
  display: flex;
  align-items: center; /* Vertically centers checkbox and text */
  gap: 12px;           /* This creates the specific horizontal space you want */
  padding: 10px 0;
  cursor: pointer;
  width: 100%;         /* Ensures it takes up the full width of the form */
}

/* Fix: Match the class name to 'Checkfull-width' */
.Checkfull-width input[type="checkbox"] {
  width: 18px;
  height: 18px;
  margin: 0;           /* Removes default browser margins */
  accent-color: green;
  cursor: pointer;
  flex-shrink: 0;      /* Prevents checkbox from squishing */
}

.Checkfull-width span {
  user-select: none;
  white-space: nowrap; /* Keeps 'Ship to a different address?' on one line */
  font-size: 16px;
  color: #333;
}
 /* .checkout-container {
    display: flex;
    justify-content: space-between;
    max-width: 1000px;
    margin: 0 auto;
    gap: 30px;
  } */

  .billing, .order-summary {
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    flex: 1;
  }

  .billing h2, .order-summary h2 {
    margin-top: 0;
    margin-bottom: 20px;
    font-size: 1.5em;
    color: #333;
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

  .billing form input:focus {
    border-color: green;
    outline: none;
  }

  .billing form .full-width {
    grid-column: span 2;
  }

  .order-summary table {
    width: 100%;
    border-collapse: collapse;
  }

  .order-summary table th, .order-summary table td {
    text-align: left;
    padding: 10px 0;
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
</style>
@endsection