@extends('Layout.client')

@section('content')
<style>
/* ── Review Section ── */
.review-section { padding: 40px 0; }
.review-card {
    background: #fff; border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    overflow: hidden; margin-bottom: 20px;
}
.review-card-header {
    padding: 16px 24px; border-bottom: 1px solid #f0f0f0;
    display: flex; align-items: center; justify-content: space-between;
}
.review-card-header h5 { margin: 0; font-weight: 700; }
.avg-rating { display: flex; align-items: center; gap: 8px; }
.avg-num { font-size: 2em; font-weight: 700; color: #d33b33; }
.stars-display { color: #f5c518; font-size: 1.1em; }
.avg-count { color: #aaa; font-size: 0.85em; }

/* Review List */
.review-list-item {
    padding: 18px 24px; border-bottom: 1px solid #f9f9f9;
}
.review-list-item:last-child { border-bottom: none; }
.reviewer-info { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
.reviewer-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: linear-gradient(135deg, #d33b33, #a01010);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 700; font-size: 1em; flex-shrink: 0;
}
.reviewer-name { font-weight: 600; color: #333; font-size: 0.92em; }
.review-date { color: #aaa; font-size: 0.78em; }
.review-stars { color: #f5c518; font-size: 0.9em; }
.review-comment { color: #555; font-size: 0.9em; line-height: 1.6; margin: 0; }

/* Write Review Form */
.write-review-card {
    background: #fff; border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    padding: 24px; margin-top: 20px;
}
.write-review-card h5 { font-weight: 700; margin-bottom: 20px; color: #333; }

/* Star Rating Input */
.star-rating-input { display: flex; flex-direction: row-reverse; gap: 4px; margin-bottom: 16px; }
.star-rating-input input { display: none; }
.star-rating-input label {
    font-size: 4em; color: #ddd; cursor: pointer;
    transition: color 0.15s;
}
.star-rating-input label:hover,
.star-rating-input label:hover ~ label,
.star-rating-input input:checked ~ label {
     color: #f5c518 !important;
}

.review-form .form-control {
    border: 1px solid #e0e0e0; border-radius: 8px;
    padding: 10px 14px; font-size: 0.9em;
    transition: border-color 0.2s;
}
.review-form .form-control:focus {
    border-color: #d33b33; box-shadow: 0 0 0 3px rgba(211,59,51,0.1);
    outline: none;
}
.review-form label { font-weight: 600; color: #555; font-size: 0.88em; margin-bottom: 6px; }
.btn-submit-review {
    background: #d33b33; color: #fff; border: none;
    padding: 10px 28px; border-radius: 8px; font-weight: 600;
    cursor: pointer; transition: background 0.2s;
}
.btn-submit-review:hover { background: #a01010; }

.no-reviews {
    text-align: center; padding: 30px; color: #aaa;
}
.no-reviews i { font-size: 2.5em; display: block; margin-bottom: 10px; color: #ddd; }
</style>
<!-- Start All Title Box -->
    <div class="all-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Shop Detail</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Shop</a></li>
                        <li class="breadcrumb-item active">Shop Detail </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->

    <!-- Start Shop Detail  -->
    <div class="shop-detail-box-main">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-5 col-md-6">
                    <div id="carousel-example-1" class="single-product-slider carousel slide" data-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active"> 
                                <img src="{{ asset($product->image) }}" class="d-block w-100" alt="{{ $product->name }}">


                                <!-- <img class="d-block w-100" src="images/big-img-01.jpg" alt="First slide">  -->
                            </div>
                            <!-- <div class="carousel-item"> <img class="d-block w-100" src="images/big-img-02.jpg" alt="Second slide"> </div> -->
                            <!-- <div class="carousel-item"> <img class="d-block w-100" src="images/big-img-03.jpg" alt="Third slide"> </div> -->
                        </div>
                        <!-- <a class="carousel-control-prev" href="#carousel-example-1" role="button" data-slide="prev"> 
						<i class="fa fa-angle-left" aria-hidden="true"></i>
						<span class="sr-only">Previous</span> 
					</a>
                        <a class="carousel-control-next" href="#carousel-example-1" role="button" data-slide="next"> 
						<i class="fa fa-angle-right" aria-hidden="true"></i> 
						<span class="sr-only">Next</span> 
					</a> -->
                        <!-- <ol class="carousel-indicators">
                            <li data-target="#carousel-example-1" data-slide-to="0" class="active">
                                <img class="d-block w-100 img-fluid" src="images/smp-img-01.jpg" alt="" />
                            </li>
                            <li data-target="#carousel-example-1" data-slide-to="1">
                                <img class="d-block w-100 img-fluid" src="images/smp-img-02.jpg" alt="" />
                            </li>
                            <li data-target="#carousel-example-1" data-slide-to="2">
                                <img class="d-block w-100 img-fluid" src="images/smp-img-03.jpg" alt="" />
                            </li>
                        </ol> -->
                    </div>
                </div>
                <div class="col-xl-7 col-lg-7 col-md-6">
                    <div class="single-product-details">
                        <h2>{{ $product->name }}</h2>

                        <h5>
                            @if($product->discount_type != 'none')
                            <del>Rs. {{ number_format($product->price, 2) }}</del>
                            Rs. {{ number_format(
                            $product->price - ($product->discount_type == 'rate'
                            ? ($product->price * $product->discount_rate / 100)
                            : $product->discount_amount),2) }}
                            @else
                            $ {{ number_format($product->price, 2) }}
                            @endif
                        </h5>


                        <p class="available-stock">
                            @if($product->quantity > 0)
                            <span>More than {{ $product->quantity }} available</span>
                            @else
                            <span style="color:red; font-weight:bold;">Sold Out</span>
                            @endif
                        </p>


                        <!-- <h5> <del>$ 60.00</del> $40.79</h5> -->
                        <!-- <p class="available-stock"><span> More than 20 available </span> -->
                            <p>
                                <h4>Short Description:</h4>                                
                                <p>{{ $product->description }}</p>

                                <!-- <p>Nam sagittis a augue eget scelerisque. Nullam lacinia consectetur sagittis. Nam sed neque id eros fermentum dignissim quis at tortor. Nullam ultricies urna quis sem sagittis pharetra. Nam erat turpis, cursus in ipsum at,
                                    tempor imperdiet metus. In interdum id nulla tristique accumsan. Ut semper in quam nec pretium. Donec egestas finibus suscipit. Curabitur tincidunt convallis arcu. </p> -->
                                <ul>
                                    <!-- <li>
                                        <div class="form-group size-st">
                                            <label class="size-label">Size</label>
                                            <select id="basic" class="selectpicker show-tick form-control">
									<option value="0">Size</option>
									<option value="0">S</option>
									<option value="1">M</option>
									<option value="1">L</option>
									<option value="1">XL</option>
									<option value="1">XXL</option>
									<option value="1">3XL</option>
									<option value="1">4XL</option>
								</select>
                                        </div>
                                    </li> -->
                                    <li>
                                        <div class="form-group quantity-box">
                                            <label class="control-label">Quantity</label>
                                            <input class="form-control" id="productQuantity" value="1" min="1" max="20" type="number">
                                        </div>
                                    </li>
                                </ul>

                                <div class="price-box-bar">
                                    <div class="cart-and-bay-btn">
                                       @if(session()->has('client_id'))
    <a class="btn hvr-hover" id="buyButton" href="javascript:void(0);"
       @if($product->quantity == 0) disabled style="pointer-events:none; opacity:0.5;" @endif>
       Buy
    </a>
@else
    <a class="btn hvr-hover" href="{{ route('ClientLogin') }}">
       Buy
    </a>
@endif

                                        <!-- <a class="btn hvr-hover" data-fancybox-close="" href="#" 
                                            @if($product->quantity == 0) disabled style="pointer-events:none; opacity:0.5;" @endif>
                                            Add to Cart
                                        </a> -->


                                        <a class="btn hvr-hover add-cart cart" 
   href="#"
   data-id="{{ $product->id }}"
   data-name="{{ $product->name }}"
   data-price="{{ $product->final_price }}"
   data-image="{{ asset($product->image) }}"
   @if($product->quantity == 0) 
       style="pointer-events:none; opacity:0.5; cursor:not-allowed;"
   @endif>
   Add to Cart
</a>
                                    </div>
                                </div>

                                <!-- <div class="add-to-btn">
                                    <div class="add-comp">
                                        <a class="btn hvr-hover" href="#"><i class="fas fa-heart"></i> Add to wishlist</a>
                                        <a class="btn hvr-hover" href="#"><i class="fas fa-sync-alt"></i> Add to Compare</a>
                                    </div>
                                    <div class="share-bar">
                                        <a class="btn hvr-hover" href="#"><i class="fab fa-facebook" aria-hidden="true"></i></a>
                                        <a class="btn hvr-hover" href="#"><i class="fab fa-google-plus" aria-hidden="true"></i></a>
                                        <a class="btn hvr-hover" href="#"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                                        <a class="btn hvr-hover" href="#"><i class="fab fa-pinterest-p" aria-hidden="true"></i></a>
                                        <a class="btn hvr-hover" href="#"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
                                    </div>
                                </div> -->
                    </div>
                </div>
            </div>

            <div class="row my-5">
    <div class="col-lg-12">
        <div class="title-all text-center">
            <h1>
                Similar Products
                <small style="font-size:0.35em; color:#28a745; font-weight:normal;">
                    ✨ AI Powered
                </small>
            </h1>
        </div>

        <div class="row special-list">
            @foreach($featuredProducts as $fp)
                @php
                    if($fp->discount_rate > 0) {
                        $fpFinal = $fp->price - ($fp->price * $fp->discount_rate / 100);
                    } elseif($fp->discount_amount > 0) {
                        $fpFinal = $fp->price - $fp->discount_amount;
                    } else {
                        $fpFinal = $fp->price;
                    }
                @endphp

                <div class="col-lg-3 col-md-6 special-grid">
                    <div class="products-single fix">
                        <div class="box-img-hover">
                            <img src="{{ asset($fp->image) }}"
                                 class="img-fluid"
                                 alt="{{ $fp->name }}"
                                 style="height:220px; object-fit:cover; width:100%;">
                            <div class="mask-icon">
                                <ul>
                                    <li>
                                        <a href="{{ route('Client.shopdetails', $fp->id) }}"
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </li>
                                </ul>
                                @if(session()->has('client_id'))
                                    <a class="add-cart cart" href="#"
                                       data-id="{{ $fp->id }}"
                                       data-name="{{ $fp->name }}"
                                       data-price="{{ $fpFinal }}"
                                       data-image="{{ asset($fp->image) }}">
                                       Add to Cart
                                    </a>
                                @else
                                    <a href="{{ route('ClientLogin') }}" class="cart">
                                        Add to Cart
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="why-text">
                            <h4>{{ $fp->name }}</h4>
                            <h5>
                                Rs.{{ number_format($fpFinal, 2) }}
                                @if($fp->discount_rate > 0 || $fp->discount_amount > 0)
                                    <del style="color:gray; font-size:0.75em;">
                                        Rs.{{ number_format($fp->price, 2) }}
                                    </del>
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

        </div>
    </div>
    <!-- End Cart -->

    {{-- ═══════════════════════════════════════════════════
     REVIEWS SECTION
════════════════════════════════════════════════════ --}}
<div class="container review-section">
    <div class="row">
        <div class="col-lg-12">

            {{-- Success Message --}}
            @if(session('review_success'))
                <div style="background:#d4edda; color:#155724; padding:12px 20px;
                            border-radius:8px; margin-bottom:20px; border:1px solid #c3e6cb;">
                    <i class="fas fa-check-circle" style="margin-right:8px;"></i>
                    {{ session('review_success') }}
                </div>
            @endif

            <div class="row">

                {{-- ── Left: Reviews List ── --}}
                <div class="col-lg-7 col-md-12 mb-4">
                    <div class="review-card">
                        <div class="review-card-header">
                            <h5>
                                <i class="fas fa-star" style="color:#f5c518; margin-right:6px;"></i>
                                Customer Reviews
                            </h5>
                            @php
                                $approvedReviews = $reviews ?? collect();
                                $avgRating = $approvedReviews->count() > 0
                                    ? round($approvedReviews->avg('rating'), 1)
                                    : 0;
                            @endphp
                            @if($approvedReviews->count() > 0)
                                <div class="avg-rating">
                                    <span class="avg-num">{{ $avgRating }}</span>
                                    <div>
                                        <div class="stars-display">
                                            @for($s = 1; $s <= 5; $s++)
                                                @if($s <= round($avgRating))
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="avg-count">
                                            {{ $approvedReviews->count() }} review(s)
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Review Items --}}
                        @if($approvedReviews->isEmpty())
                            <div class="no-reviews">
                                <i class="far fa-comment-dots"></i>
                                <p>No reviews yet. Be the first to review!</p>
                            </div>
                        @else
                            @foreach($approvedReviews as $review)
                                <div class="review-list-item">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">
                                            {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="reviewer-name">{{ $review->reviewer_name }}</div>
                                            <div class="review-date">
                                                {{ $review->created_at->format('d M Y') }}
                                            </div>
                                        </div>
                                        <div class="review-stars" style="margin-left:auto;">
                                            @for($s = 1; $s <= 5; $s++)
                                                @if($s <= $review->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="review-comment">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- ── Right: Write Review Form ── --}}
                <div class="col-lg-5 col-md-12">
                    <div class="write-review-card">
                        <h5>
                            <i class="fas fa-pen" style="color:#d33b33; margin-right:6px;"></i>
                            Write a Review
                        </h5>

                        <form action="{{ route('review.store') }}" method="POST" class="review-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            {{-- Name --}}
                            <div class="form-group mb-3">
                                <label>Your Name *</label>
                                <input type="text" name="reviewer_name" class="form-control"
                                       placeholder="Enter your name"
                                       value="{{ session('client_name') ?? old('reviewer_name') }}"
                                       required>
                                @error('reviewer_name')
                                    <small style="color:red;">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Star Rating --}}
                            <div class="form-group mb-3">
                                <label>Rating *</label>
                                <div class="star-rating-input" 
     style="display:flex; flex-direction:row-reverse; gap:6px; margin-bottom:16px;">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}"
                                               name="rating" value="{{ $i }}"
                                               {{ old('rating') == $i ? 'checked' : '' }}>
                                       <label for="star{{ $i }}" title="{{ $i }} stars"
       style="font-size:3.5em !important; color:#ddd; cursor:pointer; 
              transition:color 0.2s; line-height:1;">
    &#9733;
</label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <small style="color:red;">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Comment --}}
                            <div class="form-group mb-3">
                                <label>Your Review *</label>
                                <textarea name="comment" class="form-control"
                                          rows="4"
                                          placeholder="Share your experience with this product..."
                                          required>{{ old('comment') }}</textarea>
                                @error('comment')
                                    <small style="color:red;">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn-submit-review">
                                <i class="fas fa-paper-plane" style="margin-right:6px;"></i>
                                Submit Review
                            </button>
                            <small style="display:block; margin-top:8px; color:#aaa;">
                                * Reviews are published after admin approval
                            </small>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



    <script>
document.getElementById("buyButton").addEventListener("click", function () {

    let quantity = document.getElementById("productQuantity").value;

    let productId = "{{ $product->id }}";

    let url = "{{ route('delivery.info', ':id') }}";
    url = url.replace(':id', productId);

    // Add quantity as query parameter
    window.location.href = url + "?quantity=" + quantity;

});
</script>


@endsection