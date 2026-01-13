@extends('Layout.client')

@section('content')
<div class="all-title-box">
    <div class="container">
        <h2>Cart</h2>
    </div>
</div>

<div class="cart-box-main">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="table-main table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Images</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody id="cartBody">
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row my-5">
            <div class="col-lg-8 col-sm-12"></div>
            <div class="col-lg-4 col-sm-12">
                <div class="order-box">
                    <h3>Order summary</h3>
                    <div class="d-flex">
                        <h4>Sub Total</h4>
                        <div class="ml-auto font-weight-bold" id="subTotal">$ 0.00</div>
                    </div>
                    <div class="d-flex">
                        <h4>Discount</h4>
                        <div class="ml-auto font-weight-bold" id="discount">$ 0.00</div>
                    </div>
                    <hr class="my-1">
                    <div class="d-flex">
                        <h4>Shipping Cost</h4>
                        <div class="ml-auto font-weight-bold">Free</div>
                    </div>
                    <hr>
                    <div class="d-flex gr-total">
                        <h5>Grand Total</h5>
                        <div class="ml-auto h5 font-weight-bold" id="grandTotal">$ 0.00</div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="col-12 d-flex shopping-box">
                <a href="{{ url('checkout') }}" class="ml-auto btn hvr-hover" id="checkoutBtn">Checkout</a>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    // Wait for DOM to be fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCart);
    } else {
        initCart();
    }
    
    function initCart() {
        console.log('Initializing cart...');
        
        // Get cart from localStorage
        let cart = [];
        try {
            const cartData = localStorage.getItem("cart");
            console.log('Raw localStorage data:', cartData);
            
            if (cartData) {
                cart = JSON.parse(cartData);
                console.log('Parsed cart data:', cart);
                console.log('Cart length:', cart.length);
            }
        } catch (error) {
            console.error('Error parsing cart data:', error);
            cart = [];
        }
        
        // Get DOM elements
        const cartBody = document.getElementById("cartBody");
        const grandTotalEl = document.getElementById("grandTotal");
        const subTotalEl = document.getElementById("subTotal");
        const discountEl = document.getElementById("discount");
        const checkoutBtn = document.getElementById("checkoutBtn");
        
        // Check if elements exist
        if (!cartBody) {
            console.error('cartBody element not found!');
            return;
        }
        
        console.log('DOM elements found successfully');
        
        // Function to render cart
        function renderCart() {
            console.log('Rendering cart with', cart.length, 'items');
            
            // Clear cart body
            cartBody.innerHTML = "";
            
            // Check if cart is empty
            if (!cart || cart.length === 0) {
                cartBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h4>Your cart is empty</h4>
                            <p class="text-muted">Add some products to get started!</p>
                            <a href="{{ url('/') }}" class="btn btn-primary mt-3">Continue Shopping</a>
                        </td>
                    </tr>
                `;
                
                subTotalEl.innerText = "$ 0.00";
                discountEl.innerText = "$ 0.00";
                grandTotalEl.innerText = "$ 0.00";
                
                if (checkoutBtn) {
                    checkoutBtn.style.display = 'none';
                }
                return;
            }
            
            // Show checkout button
            if (checkoutBtn) {
                checkoutBtn.style.display = 'inline-block';
            }
            
            let subTotal = 0;
            
            // Loop through cart items
            cart.forEach((product, index) => {
                console.log('Rendering product:', product);
                
                // Ensure quantity is a valid number
                let quantity = parseInt(product.quantity) || 1;
                let price = parseFloat(product.price) || 0;
                let total = price * quantity;
                subTotal += total;
                
                // Create table row
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td class="thumbnail-img">
                        <a href="#">
                            <img class="img-fluid" src="${product.img || product.image || '/images/placeholder.png'}" 
                                 alt="${product.name || 'Product'}" 
                                 style="max-width: 80px; height: auto;"
                                 onerror="this.src='/images/placeholder.png';" />
                        </a>
                    </td>
                    <td class="name-pr">
                        <a href="#">${product.name || 'Product Name'}</a>
                    </td>
                    <td class="price-pr">
                        <p>$ ${price.toFixed(2)}</p>
                    </td>
                    <td class="quantity-box">
                        <input type="number" 
                               min="1" 
                               max="100"
                               step="1" 
                               value="${quantity}" 
                               data-index="${index}" 
                               class="c-input-text qty text form-control" 
                               style="width: 80px;">
                    </td>
                    <td class="total-pr">
                        <p>$ ${total.toFixed(2)}</p>
                    </td>
                    <td class="remove-pr">
                        <a href="#" class="remove-item" data-index="${index}">
                            <i class="fas fa-times"></i>
                        </a>
                    </td>
                `;
                cartBody.appendChild(tr);
            });
            
            // Update totals
            let discount = 0;
            let grandTotal = subTotal - discount;
            
            subTotalEl.innerText = "$ " + subTotal.toFixed(2);
            discountEl.innerText = "$ " + discount.toFixed(2);
            grandTotalEl.innerText = "$ " + grandTotal.toFixed(2);
            
            console.log('Cart rendered. SubTotal:', subTotal);
        }
        
        // Function to update cart count in header
        function updateCartCount() {
            const cartCountEl = document.getElementById("cartCount");
            if (cartCountEl) {
                cartCountEl.innerText = cart.length;
            }
        }
        
        // Function to save cart to localStorage
        function saveCart() {
            try {
                localStorage.setItem("cart", JSON.stringify(cart));
                console.log('Cart saved to localStorage');
            } catch (error) {
                console.error('Error saving cart:', error);
            }
        }
        
        // Event delegation for remove buttons
        cartBody.addEventListener("click", function(e) {
            const removeBtn = e.target.closest(".remove-item");
            if (removeBtn) {
                e.preventDefault();
                const index = parseInt(removeBtn.dataset.index);
                
                if (confirm('Are you sure you want to remove this item?')) {
                    console.log('Removing item at index:', index);
                    cart.splice(index, 1);
                    saveCart();
                    renderCart();
                    updateCartCount();
                }
            }
        });
        
        // Event delegation for quantity inputs
        cartBody.addEventListener("input", function(e) {
            if (e.target.classList.contains("qty")) {
                const index = parseInt(e.target.dataset.index);
                let value = parseInt(e.target.value);
                
                // Validate quantity
                if (isNaN(value) || value < 1) {
                    value = 1;
                    e.target.value = 1;
                }
                
                if (value > 100) {
                    value = 100;
                    e.target.value = 100;
                }
                
                console.log('Updating quantity for index', index, 'to', value);
                cart[index].quantity = value;
                saveCart();
                renderCart();
            }
        });
        
        // Initial render
        renderCart();
        updateCartCount();
    }
})();
</script>

<style>
.c-input-text {
    text-align: center;
}

.remove-item {
    color: #dc3545;
    font-size: 18px;
    transition: all 0.3s ease;
}

.remove-item:hover {
    color: #c82333;
    transform: scale(1.2);
}

.thumbnail-img img {
    border-radius: 5px;
    border: 1px solid #eee;
}

.order-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
}
</style>
@endsection