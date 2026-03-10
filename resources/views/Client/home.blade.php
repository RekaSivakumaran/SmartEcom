@extends('Layout.client')

@section('content')

<style>
.custom-cart-btn {
    margin-top: 10px;
    padding: 6px 18px;
    background: #d33b33;
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 4px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.custom-cart-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
}

/* ── Recommendation section styles ── */
#rec-section {
    display: none; /* hidden until AJAX loads */
}
#rec-loading {
    text-align: center;
    padding: 30px;
    color: #888;
}
.rec-similarity-badge {
    display: inline-block;
    background: #28a745;
    color: #fff;
    font-size: 0.7em;
    padding: 2px 7px;
    border-radius: 10px;
    margin-left: 5px;
}
</style>

{{-- ═══════════════════════════════════════════════════════════
     SLIDER
═══════════════════════════════════════════════════════════ --}}
<div id="slides-shop" class="cover-slides">
    <ul class="slides-container">
        <li class="text-left">
            <img src="{{ asset('image/ban-1.jpg') }}" alt="">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="m-b-20"><strong>Welcome To <br> SMart Ecom</strong></h1>
                        <p class="m-b-40">See how your users experience your website in realtime or view <br> trends to see any changes in performance over time.</p>
                        <p><a class="btn hvr-hover" href="{{ route('products.all') }}">Shop</a></p>
                    </div>
                </div>
            </div>
        </li>
        <li class="text-center">
            <img src="{{ asset('image/ban-2.jpg') }}" alt="">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="m-b-20"><strong>Welcome To <br> SMart Ecom</strong></h1>
                        <p class="m-b-40">See how your users experience your website in realtime or view <br> trends to see any changes in performance over time.</p>
                        <p><a class="btn hvr-hover" href="{{ route('products.all') }}">Shop</a></p>
                    </div>
                </div>
            </div>
        </li>
        <li class="text-right">
            <img src="{{ asset('image/ban-3.jpg') }}" alt="">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="m-b-20"><strong>Welcome To <br> SMart Ecom</strong></h1>
                        <p class="m-b-40">See how your users experience your website in realtime or view <br> trends to see any changes in performance over time.</p>
                        <p><a class="btn hvr-hover" href="{{ route('products.all') }}">Shop</a></p>
                    </div>
                </div>
            </div>
        </li>
    </ul>
    <div class="slides-navigation">
        <a href="#" class="next"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
        <a href="#" class="prev"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════
     AI RECOMMENDATIONS — loaded via AJAX after page load
     Only shown if logged-in user has a last-viewed/purchased product
     OR falls back to a default search term
═══════════════════════════════════════════════════════════ --}}
<div id="rec-section" class="products-box" 
     style="background:#f9f9f9; display:none; 
            position:relative; z-index:1; 
            padding: 40px 0; clear:both;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-all text-center">
                    <h1>
                        Recommended For You
                        <small style="font-size:0.35em; color:#28a745; font-weight:normal;">
                            ✨ AI Powered
                        </small>
                    </h1>
                    <p id="rec-based-on" style="color:#888; font-size:0.85em;"></p>
                </div>
            </div>
        </div>

        <div id="rec-loading" style="text-align:center; padding:20px; color:#888;">
            <i class="fas fa-spinner fa-spin"></i> Finding recommendations...
        </div>

        <div class="row" id="rec-container" 
             style="display:flex; flex-wrap:wrap; align-items:stretch;">
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     CATEGORIES
═══════════════════════════════════════════════════════════ --}}
<div class="categories-shop">
    <div class="container">
        <div class="row">
            @foreach($departments as $department)
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="shop-dep-box">
                    <img class="img-fluid"
                         src="{{ asset($department->imagepath) }}"
                         alt="{{ $department->Maincategoryname }}" />
                    <a class="btn hvr-hover" href="#">{{ $department->Maincategoryname }}</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════
     FEATURED PRODUCTS — uses $products from HomeController
═══════════════════════════════════════════════════════════ --}}
<div class="products-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-all text-center">
                    <h1>Featured Products</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="special-menu text-center">
                    <div class="button-group filter-button-group">
                        <button class="active" data-filter="*">All</button>
                        <button data-filter=".top-featured">Top featured</button>
                        <button data-filter=".best-seller">Best seller</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row special-list">
            @foreach($products as $product)
                @php
                    $classes = '';
                    if($product->created_at >= \Carbon\Carbon::now()->subDays(7)) {
                        $classes .= ' top-featured';
                    }
                    if($product->discount_amount > 0 || $product->discount_rate > 0) {
                        $classes .= ' best-seller';
                    }

                    if($product->discount_rate > 0) {
                        $finalPrice  = $product->price - ($product->price * $product->discount_rate / 100);
                        $displayRate = $product->discount_rate;
                    } elseif($product->discount_amount > 0) {
                        $finalPrice  = $product->price - $product->discount_amount;
                        $displayRate = ($product->discount_amount / $product->price) * 100;
                    } else {
                        $finalPrice  = $product->price;
                        $displayRate = 0;
                    }
                @endphp

                <div class="col-lg-3 col-md-6 special-grid{{ $classes }}">
                    <div class="products-single fix">
                        <div class="box-img-hover">
                            @if($product->discount_amount > 0 || $product->discount_rate > 0)
                                <div class="type-lb"><p class="sale">Sale</p></div>
                            @elseif($product->created_at >= \Carbon\Carbon::now()->subDays(7))
                                <div class="type-lb"><p class="new">New</p></div>
                            @endif

                            <img src="{{ asset($product->image) }}" class="img-fluid" alt="{{ $product->name }}">

                            <div class="mask-icon">
                                <ul>
                                    <li>
                                        <a href="{{ route('Client.shopdetails', $product->id) }}" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </li>
                                </ul>
                                @if(session()->has('client_id'))
                                    <a class="add-cart cart" href="#"
                                       data-id="{{ $product->id }}"
                                       data-name="{{ $product->name }}"
                                       data-price="{{ $finalPrice }}"
                                       data-image="{{ asset($product->image) }}">
                                       Add to Cart
                                    </a>
                                @else
                                    <a href="{{ route('ClientLogin') }}" class="cart">Add to Cart</a>
                                @endif
                            </div>
                        </div>

                        <div class="why-text">
                            <h4>{{ $product->name }}</h4>
                            <h5>
                                Rs.{{ number_format($finalPrice, 2) }}
                                @if($displayRate > 0)
                                    <del style="color:gray; font-size:0.6em;">Rs.{{ number_format($product->price, 2) }}</del>
                                    <small style="color:gray; font-size:0.6em;">-{{ number_format($displayRate, 0) }}%</small>
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════
     BLOG
═══════════════════════════════════════════════════════════ --}}
<div class="latest-blog">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-all text-center">
                    <h1>Latest blog</h1>
                    <p>Trends in SMart E-Commerce</p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach([['blog-img.jpg'], ['blog-img-01.jpg'], ['blog-img-02.jpg']] as $blog)
            <div class="col-md-6 col-lg-4 col-xl-4">
                <div class="blog-box">
                    <div class="blog-img">
                        <img class="img-fluid" src="{{ asset('image/' . $blog[0]) }}" alt="" />
                    </div>
                    <div class="blog-content">
                        <div class="title-blog">
                            <h3>Boost Your Online Store: Latest E-Commerce Insights</h3>
                            <p>The world of e-commerce is evolving rapidly. AI-powered personalization, social commerce, and mobile-first website design are essential for growth.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════
     JAVASCRIPT
═══════════════════════════════════════════════════════════ --}}
<script>
// ── Product filter buttons ───────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.filter-button-group button');
    const items   = document.querySelectorAll('.special-list .special-grid');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.getAttribute('data-filter');
            items.forEach(item => {
                if (filter === '*' || item.classList.contains(filter.replace('.', ''))) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {

    @if(session()->has('client_id'))
        // Login பண்ணிய user → purchased products-ஐ வைத்து recommend
        loadPurchasedRecommendations();
    @else
        // Login இல்லாதவர் → last viewed product அல்லது default
        const lastViewed = localStorage.getItem('last_viewed_product');
        if (lastViewed) {
            loadRecommendations(lastViewed);
        }
        // Login இல்லாதவர்களுக்கு section hide பண்ணு
    @endif

});

// ── Login user-இன் purchased products recommendations ──
function loadPurchasedRecommendations() {
    const section   = document.getElementById('rec-section');
    const loading   = document.getElementById('rec-loading');
    const container = document.getElementById('rec-container');
    const basedOn   = document.getElementById('rec-based-on');

    section.style.display = 'block';
    loading.style.display = 'block';
    container.innerHTML   = '';

    // GET request — CSRF token தேவையில்லை
    fetch('{{ route("recommendations.purchased") }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        loading.style.display = 'none';

        // Empty array வந்தால் — last viewed try பண்ணு
        if (!data || (!data.recommendations && !Array.isArray(data)) || 
            (data.recommendations && data.recommendations.length === 0) ||
            (Array.isArray(data) && data.length === 0)) {
            
            const lastViewed = localStorage.getItem('last_viewed_product');
            if (lastViewed) {
                loadRecommendations(lastViewed);
            } else {
                section.style.display = 'none';
            }
            return;
        }

        // "Based on your purchase of: WHITE MUG" காட்டு
        if (data.based_on) {
            basedOn.textContent = 'Based on your recent purchase of: ' + data.based_on;
        }

        const products = data.recommendations || data;
        renderProducts(products);
    })
    .catch(err => {
        console.warn('Purchased recommendations error:', err);
        section.style.display = 'none';
    });
}

// ── Product name கொடுத்து recommendations ──
function loadRecommendations(productName) {
    const section   = document.getElementById('rec-section');
    const loading   = document.getElementById('rec-loading');
    const container = document.getElementById('rec-container');

    section.style.display = 'block';
    loading.style.display = 'block';
    container.innerHTML   = '';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch('{{ route("recommendations.ajax") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ product_name: productName, n: 6 })
    })
    .then(res => res.json())
    .then(data => {
        loading.style.display = 'none';

        if (!data || data.length === 0) {
            section.style.display = 'none';
            return;
        }

        renderProducts(data);
    })
    .catch(err => {
        console.warn('Recommendations unavailable:', err);
        section.style.display = 'none';
    });
}

// ── Product cards render பண்ணு ──
function renderProducts(products) {
    const section   = document.getElementById('rec-section');
    const container = document.getElementById('rec-container');

    if (!products || products.length === 0) {
        section.style.display = 'none';
        return;
    }

    container.innerHTML = '';

   container.innerHTML = '';  

products.forEach(product => {
    const discountHtml = product.display_rate > 0
        ? `<del style="color:gray;font-size:0.6em;">
               Rs.${parseFloat(product.price).toFixed(2)}
           </del>
           <small style="color:gray;font-size:0.6em;">
               -${product.display_rate}%
           </small>`
        : '';

    const addCartBtn = {{ session()->has('client_id') ? 'true' : 'false' }}
        ? `<a class="add-cart cart" href="#"
               data-id="${product.id}"
               data-name="${product.name}"
               data-price="${product.final_price}"
               data-image="${product.image}">
               Add to Cart
           </a>`
        : `<a href="{{ route('ClientLogin') }}" class="cart">Add to Cart</a>`;

    container.innerHTML += `
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" 
             style="margin-bottom:30px;">
            <div class="products-single fix" 
                 style="height:100%; background:#fff; 
                        border-radius:4px; overflow:hidden;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div class="box-img-hover" 
                     style="position:relative; overflow:hidden; height:220px;">
                    <img src="${product.image}" 
                         class="img-fluid" 
                         alt="${product.name}"
                         style="width:100%; height:220px; 
                                object-fit:cover; display:block;">
                    <div class="mask-icon">
                        <ul>
                            <li>
                                <a href="${product.detail_url}" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </li>
                        </ul>
                        ${addCartBtn}
                    </div>
                </div>
                <div class="why-text" style="padding:15px;">
                    <h4 style="font-size:0.95em; margin-bottom:8px;">
                        ${product.name}
                        <span style="display:inline-block; background:#28a745; 
                                     color:#fff; font-size:0.6em; padding:2px 7px; 
                                     border-radius:10px; margin-left:4px;">
                            ${Math.round(product.similarity * 100)}% match
                        </span>
                    </h4>
                    <h5 style="color:#d33b33; font-weight:bold;">
                        Rs.${parseFloat(product.final_price).toFixed(2)}
                        ${discountHtml}
                    </h5>
                </div>
            </div>
        </div>`;
});
}
</script>

@endsection