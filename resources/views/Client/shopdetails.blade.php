@extends('Layout.client')

@section('content')
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