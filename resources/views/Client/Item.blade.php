
@extends('Layout.client')

@section('content')

<style>
 .product-card {
    background: #fff;
    font-family: Arial, sans-serif;
    margin-bottom: 20px;
    border: 1px solid #eee;
}

/* IMAGE CONTAINER */
.image-box {
    position: relative;
    width: 100%;
    height: 350px;              /* SAME HEIGHT FOR ALL */
    background: #f5f5f5;        /* Placeholder background */
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.image-box img {
    width: 100%;               /* stretch to container width */
    height: 100%;              /* stretch to container height */
    object-fit: cover;         /* crop edges if needed to fill space */
    transition: transform 0.3s ease;
}

/* HOVER ZOOM */
.image-box:hover img {
    transform: scale(1.05);
}

/* VIEW ICON */
.view-icon {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #e53935;
    color: #fff;
    padding: 8px 10px;
    border-radius: 4px;
    cursor: pointer;
    z-index: 2;
    transition: transform 0.3s ease;
}

.view-icon a i {
    color: #fff;        /* force icon color white */
}

.view-icon a {
    color: #fff;        /* make the icon white */
    text-decoration: none; /* remove underline */
}

.view-icon a:hover {
    color: #fff;        /* keep white on hover */
}


/* SALE BADGE */
.sale-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: red;
    color: #fff;
    padding: 6px 12px;
    font-size: 14px;
    font-weight: bold;
    z-index: 2;
}

/* ADD TO CART */
.add-cart {
    position: absolute;
    bottom: 0;
    width: 100%;
    background: #e53935;
    color: #fff;
    text-align: center;
    padding: 12px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
}

.add-cart:hover {
    background: #c62828;
}

.view-icon:hover{
    background: #c62828;
    transform: scale(1.10);
}

/* PRODUCT INFO */
.product-info {
     padding: 5px 5px; /* smaller padding top/bottom */
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 2px; /* very small space between name and price */
    min-height: 80px; 
  
}

.price {
     margin: 0;            
    font-size: 1rem;
    font-weight: 600;
    line-height: 1.2;     
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.product-info .price {
    margin: 0;            
    font-size: 0.95rem;
    color: #222;
    line-height: 1.2;
}

.product-info del {
    margin-left: 5px;
    color: gray;
}

.product-info small {
    margin-left: 5px;
    color: #ff4c4c;
    font-weight: 600;
}


.custom-slider-container{
    position: relative;
    padding: 25px 0;
}

/* RANGE INPUTS */
.custom-slider-container input[type=range]{
    -webkit-appearance: none;
    appearance: none;
    width: 100%;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    pointer-events: none;
    z-index: 3;
}

/* THUMBS */
.custom-slider-container input[type=range]::-webkit-slider-thumb{
    -webkit-appearance: none;
    pointer-events: all;
    width: 18px;
    height: 18px;
    background: #d33b33;
    border-radius: 50%;
    border: 2px solid #fff;
    cursor: pointer;
}

.custom-slider-container input[type=range]::-moz-range-thumb{
    pointer-events: all;
    width: 18px;
    height: 18px;
    background: #d33b33;
    border-radius: 50%;
    border: 2px solid #fff;
    cursor: pointer;
}

/* HIDE DEFAULT TRACK */
.custom-slider-container input[type=range]::-webkit-slider-runnable-track{
    background: transparent;
}

.custom-slider-container input[type=range]::-moz-range-track{
    background: transparent;
}

/* CUSTOM TRACK */
.slider-track{
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    height: 6px;
    width: 100%;
    background: #050000ff;
    border-radius: 3px;
    z-index: 1;
}

/* VALUES */
.slider-values{
    margin-bottom: 35px;
    font-weight: bold;
    color: #d33b33;
    text-align: center;
}

/* BUTTON */
.btn{
    margin-top: 10px;
     
    padding: 6px 18px;
    background: #d33b33;
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}

    </style>

    <!-- Start All Title Box -->
    <div class="all-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Shop</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Shop</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->

    <!-- Start Shop Page  -->
    <div class="shop-box-inner">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-sm-12 col-xs-12 sidebar-shop-left">
                    <div class="product-categori">

                       <!-- <div class="search-product">
    <form action="javascript:void(0);">
        <input class="form-control"
               id="productSearch"
               placeholder="Search here..."
               type="text">
        <button type="submit">
            <i class="fa fa-search"></i>
        </button>
    </form>
</div> -->

 <p style="font-size:18px; font-style:italic;" class="small text-muted">
    Showing all {{ $products->count() }} results
</p>


<div class="filter-sidebar-left">
    <div class="title-left">
        <h3>Categories</h3>
    </div>

    <div class="list-group list-group-sm" id="categoryAccordion">

        <!-- ALL -->
        <a href="{{ route('products.all') }}"
           class="list-group-item list-group-item-action fw-bold">
            All
        </a>

        @foreach($categories as $main)
            <!-- MAIN CATEGORY -->
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse"
               href="#subCategory{{ $main->id }}"
               role="button"
               aria-expanded="false"
               aria-controls="subCategory{{ $main->id }}">
                {{ $main->Maincategoryname }}
                <span class="badge bg-secondary">
                    {{ $main->subCategories->count() }}
                </span>
            </a>

            <!-- SUB CATEGORY -->
            <div class="collapse"
                 id="subCategory{{ $main->id }}"
                 data-bs-parent="#categoryAccordion">
                <div class="list-group ms-3">
                    @foreach($main->subCategories as $sub)
                        <a href="{{ route('products.bySubCategory', $sub->id) }}"
                           class="list-group-item list-group-item-action">
                            {{ $sub->sub_category_name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>
</div>



@php
$finalPrices = $products->map(function($product){
    if($product->discount_rate > 0){
        return $product->price - ($product->price * $product->discount_rate / 100);
    } elseif($product->discount_amount > 0){
        return $product->price - $product->discount_amount;
    } else {
        return $product->price;
    }
});

$minPrice = floor($finalPrices->min());
$maxPrice = ceil($finalPrices->max());
@endphp

<div class="filter-price-left">
    <div class="title-left"><h3>Price</h3></div>

    <div class="custom-slider-container">
        <input type="range" id="minPriceRange" min="{{ $minPrice }}" max="{{ $maxPrice }}" value="{{ $minPrice }}">
        <input type="range" id="maxPriceRange" min="{{ $minPrice }}" max="{{ $maxPrice }}" value="{{ $maxPrice }}">
        <div class="slider-track"></div>
        <div class="slider-values">
            <span id="minVal">Rs.{{ $minPrice }}</span> - <span id="maxVal">Rs.{{ $maxPrice }}</span>
        </div>
        <!-- <button id="filterPriceBtn" class="btn">Filter</button> -->
    </div>
</div>


<div class="filter-brand-left">
    <div class="title-left">
        <h3>Brand</h3>
    </div>
    <div class="brand-box">
        <ul>
            @php
                
                $brandWithProducts = $products->pluck('brand_id')->unique();
            @endphp

            @foreach($brands as $brand)
                @if($brandWithProducts->contains($brand->id))
                <li>
                    <div class="radio radio-danger">
                        <input type="radio"
                               name="brand_filter"
                               class="brand-filter"
                               data-brand-id="{{ $brand->id }}">
                        <label>{{ $brand->brandname }}</label>
                    </div>
                </li>
                @endif
            @endforeach

            <li>
                <button id="clearBrand" style="color:#fff;" class="btn hvr-hover mt-2">Clear</button>
            </li>
        </ul>
    </div>
</div>








                        <!-- <div class="filter-brand-left">
                            <div class="title-left">
                                <h3>Brand</h3>
                            </div>
                            <div class="brand-box">
                                <ul>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios1" value="Yes" type="radio">
                                            <label for="Radios1"> Supreme </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios2" value="No" type="radio">
                                            <label for="Radios2"> A Bathing Ape </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios3" value="declater" type="radio">
                                            <label for="Radios3"> The Hundreds </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios4" value="declater" type="radio">
                                            <label for="Radios4"> Alife </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios5" value="declater" type="radio">
                                            <label for="Radios5"> Neighborhood </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios6" value="declater" type="radio">
                                            <label for="Radios6"> CLOT </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios7" value="declater" type="radio">
                                            <label for="Radios7"> Acapulco Gold </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios8" value="declater" type="radio">
                                            <label for="Radios8"> UNDFTD </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios9" value="declater" type="radio">
                                            <label for="Radios9"> Mighty Healthy </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios10" value="declater" type="radio">
                                            <label for="Radios10"> Fiberops </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div> -->

                    </div>
                </div>



                <div class="col-xl-9 col-lg-9 col-sm-12 col-xs-12 shop-content-right">
                    <div class="right-product-box">
                        <div class="product-item-filter row">
                            <div class="col-12 col-sm-8 text-center text-sm-left">

                            <div class="search-product">
    <form action="javascript:void(0);">
        <input class="form-control"
               id="productSearch"
               placeholder="Search here..."
               type="text">
        <button type="submit">
            <i class="fa fa-search"></i>
        </button>
    </form>
</div>
                                <!-- <div class="toolbar-sorter-right">
                                    <span>Sort by </span>
                                    <select id="basic" class="selectpicker show-tick form-control" data-placeholder="$ USD">
									<option data-display="Select">Nothing</option>
									<option value="1">Popularity</option>
									<option value="2">High Price → High Price</option>
									<option value="3">Low Price → High Price</option>
									<option value="4">Best Selling</option>
								</select>
                                </div> -->
                               
                            </div>
                            <div class="col-12 col-sm-4 text-center text-sm-right">
                                <ul class="nav nav-tabs ml-auto">
                                    <li>
                                        <a class="nav-link active" href="#grid-view" data-toggle="tab"> <i class="fa fa-th"></i> </a>
                                    </li>
                                    <li>
                                        <a class="nav-link" href="#list-view" data-toggle="tab"> <i class="fa fa-list-ul"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </div>




                        <div class="row product-categorie-box">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade show active" id="grid-view">
                                   
 
<div class="row g-3" id="grid-view">
@forelse($products as $product)
    @php
        // Calculate final price
        if($product->discount_rate > 0) {
            $finalPrice = $product->price - ($product->price * $product->discount_rate / 100);
            $displayRate = $product->discount_rate;
        } elseif($product->discount_amount > 0) {
            $finalPrice = $product->price - $product->discount_amount;
            $displayRate = ($product->discount_amount / $product->price) * 100;
        } else {
            $finalPrice = $product->price;
            $displayRate = 0;
        }
    @endphp

    <div class="col-sm-6 col-md-4 col-lg-4 product-item" data-brand-id="{{ $product->brand_id }}" data-price="{{ $finalPrice }}"
    data-name="{{ strtolower($product->name) }}">
        <div class="product-card" >

            <div class="image-box">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">

                <span class="view-icon">
                    <li>
                                <a href="{{ route('Client.shopdetails', $product->id) }}" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </li>
                </span>

                @if($product->is_sale)
                    <span class="sale-badge">SALE</span>
                @endif

                <div class="add-cart btn btn-primary mt-1" data-id="{{ $product->id }}">Add to Cart</div>

                <!-- <div class="add-cart">Add to Cart</div> -->
            </div>

            <div class="product-info">
                <h3 class="price">{{ $product->name }}</h3>

                <h5 class="price">
                    Rs.{{ number_format($finalPrice, 2) }}
                    
                    @if($displayRate > 0)
                        <del style="color: gray; font-size: 0.8em;">
                            Rs.{{ number_format($product->price, 2) }}
                        </del>
                        <small style="color: gray; font-size: 0.8em;">
                            -{{ number_format($displayRate, 0) }}%
                        </small>
                    @endif
                </h5>
            </div>

        </div>
    </div>
@empty
    <div class="col-12">
        <p class="text-center">No products found</p>
    </div>
@endforelse
</div>


                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="list-view">
                                    <div class="list-view-box" id="list-view">
@forelse($products as $product)

    @php
        // Calculate final price
        if($product->discount_rate > 0) {
            $finalPrice = $product->price - ($product->price * $product->discount_rate / 100);
            $displayRate = $product->discount_rate;
        } elseif($product->discount_amount > 0) {
            $finalPrice = $product->price - $product->discount_amount;
            $displayRate = ($product->discount_amount / $product->price) * 100;
        } else {
            $finalPrice = $product->price;
            $displayRate = 0;
        }
    @endphp

    <div class="row mb-4 product-item" data-brand-id="{{ $product->brand_id }}" data-price="{{ $finalPrice }}"
    data-name="{{ strtolower($product->name) }}">
        
        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-4">
            <div class="products-single fix">
                <div class="box-img-hover">

                @if($product->discount_amount > 0 || $product->discount_rate > 0)
    <div class="type-lb">
        <p class="sale">Sale</p>
    </div>
@elseif($product->created_at->gte(\Carbon\Carbon::now()->subDays(7)))
    <div class="type-lb">
        <p class="new">New</p>
    </div>
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
                    </div>

                </div>
            </div>
        </div>

        <!-- CONTENT COLUMN -->
        <div class="col-sm-6 col-md-6 col-lg-8 col-xl-8">
            <div class="why-text full-width">

                <h4>{{ $product->name }}</h4>

                <h5>
                    Rs.{{ number_format($finalPrice, 2) }}

                    @if($displayRate > 0)
                        <del>Rs.{{ number_format($product->price, 2) }}</del>
                        <small>-{{ number_format($displayRate, 0) }}%</small>
                    @endif
                </h5>

                <p>
                    {{ Str::limit($product->description, 250) }}
                </p>

                <!-- <a class="btn hvr-hover" href="#">
                    Add to Cart
                </a> -->

                <a href="javascript:void(0)" 
   class="btn hvr-hover add-cart" 
   data-id="{{ $product->id }}">
    Add to Cart
</a>

            </div>
        </div>
    </div>

@empty
    <p class="text-center">No products found</p>
@endforelse
</div>

                                     
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Shop Page -->

   <script>
document.addEventListener("DOMContentLoaded", function () {

    const minSlider = document.getElementById("minPriceRange");
    const maxSlider = document.getElementById("maxPriceRange");
    const minValDisplay = document.getElementById("minVal");
    const maxValDisplay = document.getElementById("maxVal");
    const brandRadios = document.querySelectorAll(".brand-filter");
    const clearBrandBtn = document.getElementById("clearBrand");

    const productItems = document.querySelectorAll(".product-item");

    let selectedBrand = null;

    function updateSliderUI() {
        let minVal = parseInt(minSlider.value);
        let maxVal = parseInt(maxSlider.value);

        if (minVal > maxVal) {
            [minSlider.value, maxSlider.value] = [maxVal, minVal];
            [minVal, maxVal] = [maxVal, minVal];
        }

        minValDisplay.innerText = "Rs." + minVal;
        maxValDisplay.innerText = "Rs." + maxVal;
    }

    function applyFilters() {
        const minVal = parseInt(minSlider.value);
        const maxVal = parseInt(maxSlider.value);

        productItems.forEach(item => {
            const price = parseFloat(item.dataset.price);
            const brandId = item.dataset.brandId;

            const priceMatch = price >= minVal && price <= maxVal;
            const brandMatch = selectedBrand === null || brandId === selectedBrand;

            if (priceMatch && brandMatch) {
                item.style.display = "";
            } else {
                item.style.display = "none";
            }
        });
    }

    /* PRICE EVENTS */
    minSlider.addEventListener("input", () => {
        updateSliderUI();
        applyFilters();
    });

    maxSlider.addEventListener("input", () => {
        updateSliderUI();
        applyFilters();
    });

    /* BRAND EVENTS */
    brandRadios.forEach(radio => {
        radio.addEventListener("change", function () {
            selectedBrand = this.dataset.brandId;
            applyFilters();
        });
    });

    clearBrandBtn.addEventListener("click", function () {
        selectedBrand = null;
        brandRadios.forEach(r => r.checked = false);
        applyFilters();
    });

    // Initial load
    updateSliderUI();
    applyFilters();
});
</script>


 <script>
document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("productSearch");
    const productItems = document.querySelectorAll(".product-item");

    searchInput.addEventListener("keyup", function () {

        const searchValue = this.value.toLowerCase().trim();

        productItems.forEach(item => {
            const productName = item.dataset.name;

            if (productName.includes(searchValue)) {
                item.style.display = "";
            } else {
                item.style.display = "none";
            }
        });
    });

});
</script>


@endsection