
@extends('Layout.client')


@section('content')


<!-- Start Slider -->
    <div id="slides-shop" class="cover-slides">
        <ul class="slides-container">
            <li class="text-left">
                <img src="{{ asset('image/ban-1.jpg') }}" alt="">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="m-b-20"><strong>Welcome To <br> SMart Ecom</strong></h1>
                            <p class="m-b-40">See how your users experience your website in realtime or view <br> trends to see any changes in performance over time.</p>
                            <p><a class="btn hvr-hover" href="#">Shop New</a></p>
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
                            <p><a class="btn hvr-hover" href="#">Shop New</a></p>
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
                            <p><a class="btn hvr-hover" href="#">Shop New</a></p>
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
    <!-- End Slider -->



<!-- Start Categories  -->
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


    <!-- Start Products  -->
    <div class="products-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-all text-center">
                        <h1>Featured Products</h1>
                        <!-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet lacus enim.</p> -->
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
           <!-- Products Section -->
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

                            <!-- <li><a href="#" title="View"><i class="fas fa-eye"></i></a></li> -->
                            <li><a href="#" title="Compare"><i class="fas fa-sync-alt"></i></a></li>
                            <li><a href="#" title="Add to Wishlist"><i class="far fa-heart"></i></a></li>
                        </ul>
                        <a class="cart" href="#">Add to Cart</a>
                    </div>
                </div>
                <div class="why-text">
                    <h4>{{ $product->name }}</h4>
                   @php
    // Calculate final price
    if($product->discount_rate > 0) {
        $finalPrice = $product->price - ($product->price * $product->discount_rate / 100);
    } elseif($product->discount_amount > 0) {
        $finalPrice = $product->price - $product->discount_amount;
    } else {
        $finalPrice = $product->price;
    }
@endphp

@php
    // Calculate final price
    if($product->discount_rate > 0) {
        $finalPrice = $product->price - ($product->price * $product->discount_rate / 100);
        $displayRate = $product->discount_rate;
    } elseif($product->discount_amount > 0) {
        $finalPrice = $product->price - $product->discount_amount;
        // calculate discount rate from amount
        $displayRate = ($product->discount_amount / $product->price) * 100;
    } else {
        $finalPrice = $product->price;
        $displayRate = 0;
    }
@endphp

<h5>
    Rs.{{ number_format($finalPrice, 2) }}
    @if($product->discount_amount > 0 || $product->discount_rate > 0)
        
        <del style="color: gray; font-size: 0.6em;">
            Rs.{{ number_format($product->price, 2) }}
        </del>
        <small style="color: gray; font-size: 0.6em;">
            - {{ number_format($displayRate, 0) }}%
        </small>
    @endif
</h5>



                    <!-- <h5> Rs.{{ $product->price - $product->discount_amount }}</h5> -->
                </div>
            </div>
        </div>
    @endforeach
</div>

        </div>
    </div>
    <!-- End Products  -->

    <!-- Start Blog  -->
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
                <div class="col-md-6 col-lg-4 col-xl-4">
                    <div class="blog-box">
                        <div class="blog-img">
                            <img class="img-fluid" src="{{ asset('image/blog-img.jpg') }}" alt="" />
                        </div>
                        <div class="blog-content">
                            <div class="title-blog">
                                <h3>Boost Your Online Store: Latest E-Commerce Insights</h3>
                                <p>The world of e-commerce is evolving rapidly. To provide the best experience for customers, AI-powered personalization, social commerce, and mobile-first website design are essential. 
    High-quality product descriptions, clear pricing, and professional layouts can boost sales and build trust.</p>
                            </div>
                            <ul class="option-blog">
                                <li><a href="#" data-toggle="tooltip" data-placement="right" title="Likes"><i class="far fa-heart"></i></a></li>
                                <li><a href="#" data-toggle="tooltip" data-placement="right" title="Views"><i class="fas fa-eye"></i></a></li>
                                <li><a href="#" data-toggle="tooltip" data-placement="right" title="Comments"><i class="far fa-comments"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-4">
                    <div class="blog-box">
                        <div class="blog-img">
                            <img class="img-fluid" src="{{ asset('image/blog-img-01.jpg') }}" alt="" />
                        </div>
                        <div class="blog-content">
                            <div class="title-blog">
                                <h3>Boost Your Online Store: Latest E-Commerce Insights</h3>
                                <p>The world of e-commerce is evolving rapidly. To provide the best experience for customers, AI-powered personalization, social commerce, and mobile-first website design are essential. 
    High-quality product descriptions, clear pricing, and professional layouts can boost sales and build trust.</p>
                     </div>
                            <ul class="option-blog">
                                <li><a href="#" data-toggle="tooltip" data-placement="right" title="Likes"><i class="far fa-heart"></i></a></li>
                                <li><a href="#" data-toggle="tooltip" data-placement="right" title="Views"><i class="fas fa-eye"></i></a></li>
                                <li><a href="#" data-toggle="tooltip" data-placement="right" title="Comments"><i class="far fa-comments"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-4">
                    <div class="blog-box">
                        <div class="blog-img">
                            <img class="img-fluid" src="{{ asset('image/blog-img-02.jpg') }}" alt="" />
                        </div>
                        <div class="blog-content">
                            <div class="title-blog">
                               <h3>Boost Your Online Store: Latest E-Commerce Insights</h3>
                                <p>The world of e-commerce is evolving rapidly. To provide the best experience for customers, AI-powered personalization, social commerce, and mobile-first website design are essential. 
    High-quality product descriptions, clear pricing, and professional layouts can boost sales and build trust.</p>
                     </div>
                            <ul class="option-blog">
                                <li><a href="#" data-toggle="tooltip" data-placement="right" title="Likes"><i class="far fa-heart"></i></a></li>
                                <li><a href="#" data-toggle="tooltip" data-placement="right" title="Views"><i class="fas fa-eye"></i></a></li>
                                <li><a href="#" data-toggle="tooltip" data-placement="right" title="Comments"><i class="far fa-comments"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Blog  -->


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.filter-button-group button');
        const items = document.querySelectorAll('.special-list .special-grid');

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filter = btn.getAttribute('data-filter'); // *, .top-featured, .best-seller

                items.forEach(item => {
                    if(filter === '*' || item.classList.contains(filter.replace('.', ''))) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>



    @endsection

    
