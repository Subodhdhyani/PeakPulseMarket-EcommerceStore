<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}"><!--the second one is variable and the value comes inside this variable is from ajax-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Product Checkout - PeakPulseMarket</title>
  @include('include.favicon')
  @include('include.fontawesome')
  @include('include.bootstrap')
  @include('include.spinner')
  <style>
    body {
      background-color: whitesmoke;
    }

    .toastr-success {
      background-color: green !important;
    }

    .toastr-error {
      background-color: red !important;
    }

    .custom-div {
      background-color: white;
    }

    .row-flex {
      display: flex;
      align-items: stretch;
    }

    .image-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100%;
    }

    /*.image-container img {
      max-width: 100%;
      max-height: 100%;
    }*/
    .image-container img {
      max-width: 100%;
      max-height: 300px;
      /* Adjust this value as needed */
      width: auto;
      height: auto;
      object-fit: contain;
      /*the image maintains its aspect ratio */
    }

    /* Remove padding from columns as default in bootstrap*/
    .no-padding-col {
      padding-right: 0;
      padding-left: 0;
    }

    .text_align {
      padding-left: 20px;
    }

    .small-box {
      display: flex;
      flex-direction: row;
      align-items: center;
    }

    .quantity_fetch {
      background-color: #4caf50;
      color: #fff;
      padding: 2px 5px;
      border-radius: 5px;
      font-size: 12px;
    }

    .category_fetch {
      background-color: darkkhaki;
      color: black;
      padding: 2px 5px;
      border-radius: 5px;
      font-size: 12px;
    }

    .out-of-stock {
      background-color: red;
    }

    .review {
      width: 96%;
      margin: 2%;
      background-color: white;
      border-radius: 5px;
      padding: 10px;
    }

    .rating_box {
      background-color: #4caf50;
      color: #fff;
      padding: 2px 5px;
      border-radius: 5px;
      font-size: 14px;
      margin: 2px;
    }

    /* Add margin between columns on small screens 
    @media (max-width: 767px) {
      .col-md-6 {
        margin-bottom: 10px;
      }
    }*/
  </style>
</head>

<body>
  {{--navbar--}}
  <x-navbar />
  <div class="dynamic-search-result-show-then-hide-this">{{--Inside navbar Component--}}
    <h5 class="text-center mt-4 mb-4 no_record_class text-danger" style="display: none;">Sorry Something Wrong While Displaying the Product with this ProductId.</h5>
    <div class="container-fluid mt-4" style="display: none;">
      <div class="row row-flex">
        <div class="col-md-6 no-padding-col">
          <div class="custom-div image-container">
            <img src="{{asset('category/pulse.jpeg')}}" class="product_image_fetch" alt="Product Image" max-width="100%">
          </div>
        </div>

        <div class="col-md-6 no-padding-col">
          <div class="custom-div text_align">
            <h1 class="name_fetch">Product Name</h1>
            <div class="small-box">
              <div class="quantity_fetch">Stock</div>&emsp;
              <div class="category_fetch">Category</div>
            </div>
            {{--<p class="quantity_fetch">Stock</p>--}}
            <p class="price_fetch fs-4">All Prices</p>
            <p class="text-danger">* Inclusive of all taxes</p>
            <p class="description_fetch text-justify">Product Description</p>
            <p class="weight_fetch" style="font-weight: bold;">Weight</p>

            {{--Here USed for adding product to cart by sending data by form--}}
            <form id="add_to_cart_form">
              <input type="hidden" name="product_id" class="product_id" value="">
              <input type="hidden" name="product_quantity" class="product_quantity" value="1" min="1">
              {{--Send this for session and when auth then its not required--}}
              <input type="hidden" name="category_name" class="category_name" value="">
              <input type="hidden" name="product_name" class="product_name" value="">
              <input type="hidden" name="original_price" class="original_price" value="">
              <input type="hidden" name="sale_price" class="sale_price" value="">
              <input type="hidden" name="discount" class="discount" value="">
              <input type="hidden" name="product_image" class="product_image" value="">
              {{--End for session data send--}}

              <button class="mb-4 btn btn-warning text-light add_cart_button">Add to Cart</button> {{--add_cart_button class for hide when out of stock by Javascript--}}
            </form>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="mx-auto"> <!-- Added mx-auto class here for table center -->
            <h5>Other Details</h5>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Delivery Charges</th>
                  <th>Return Policy</th>
                  <th>Manufacturer</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Flat Rs. 80 below Rs. 1000, free above.</td>
                  <td>7-day return policy: Unused items with original tags accepted.</td>
                  <td>Peak Pulse Market</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-md-12">
          <div class="review">
            <h5 class="text-secondary">Reviews</h5>
            <div class="review_content">
              {{-- Now Dynamicallly by js
                   <p class="review_rating_fetch rating_box d-inline">4 <i class="fas fa-star"></i></p>
                    <p class="d-inline review_message_fetch">This product is amazing! I love how it performs and the quality is excellent.</p>
                    <p><strong class="review_name_fetch">PeakPulseMarket</strong> - <span class="review_date_fetch">Nov 20, 2024</span></p>--}}
            </div>
          </div>
        </div>
      </div>


    </div>


  </div>
  {{--Footer--}}
  <x-footer />
  <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
  <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
  {{--cart item counts at navbar--}}
  <script src="{{ asset('js/nav_cart_count.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    $(document).ready(function() {
      var routeid = "{{ $fetched_id }}"; //fetched id pass from controller 
      // var routeid = 2;


      $.ajax({
        //url: '/product_checkout_fetch/' + routeid,

        //url: '{{-- route("product_checkout_fetch", ["id" => 4])--}}',

        //Since we doesn't directly pass routeid inside url that why we use replace
        url: '{{ route("product_checkout_fetch", ["id" => ":routeid"]) }}'.replace(':routeid', routeid),

        type: 'GET',
        success: function(response) {
          if (response.status === 'success') {
            var fetch = response.message;
            if (fetch.length === 0) {
              $('.no_record_class').show();
            } else {
              $('.container-fluid').show(); //Initially box is hidden and show when fetch successfully
              $('.name_fetch').text(fetch.product_name);
              var imageUrl = "{{ asset('storage/product_image/') }}/" + fetch.product_image;
              $('.product_image_fetch').attr('src', imageUrl);
              //$('.quantity_fetch').text(fetch.quantity);
              var quantity = fetch.quantity;
              if (quantity > 0) {
                $('.quantity_fetch').text('In Stock');
              } else {
                $('.quantity_fetch').text('Out of Stock');

                //$('.add_cart_button').hide();
                $('.add_cart_button').prop('disabled', true);
                $('.add_cart_button').text('Out of Stock');
                $('.quantity_fetch').addClass('out-of-stock');
              }
              $('.category_fetch').text(fetch.category_name);
              var priceText = '';
              priceText += '<strong>&#x20B9;' + fetch.sale_price + '</strong> ';
              priceText += '<del>&#x20B9;' + fetch.original_price + '</del> ';
              priceText += '<span style="font-size: 20px; color: green;">' + fetch.discount + '% off</span>';
              $('.price_fetch').html(priceText);
              //$('.price_fetch').text('Rs' + fetch.sale_price);
              $('.description_fetch').text(fetch.description);
              $('.weight_fetch').text('Weight : ' + fetch.weight + ' gm');

              //for review display after product_id fetch
              $('.product_id').val(fetch.id);
              fetchReviews();

              //Send product to store inside cart when click on ADD TO CART
              $('.add_cart_button').click(function(event) {
                event.preventDefault();
                //for cart 
                var product_id = fetch.id;
                var product_quantity = $('.product_quantity').val();
                //for session
                var category_name = fetch.category_name;
                var product_name = fetch.product_name;
                var original_price = fetch.original_price;
                var sale_price = fetch.sale_price;
                var discount = fetch.discount;
                var product_image = fetch.product_image;
                $.ajax({
                  url: "{{route('add_to_cart')}}",
                  type: 'POST',
                  data: {
                    product_id: product_id,
                    product_quantity: product_quantity,
                    category_name: category_name,
                    product_name: product_name,
                    original_price: original_price,
                    sale_price: sale_price,
                    discount: discount,
                    product_image: product_image,

                  },
                  headers: { //for csf token also add meta tag at head tag
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  success: function(response) {
                    if (response.status === 'success') {
                      //alert(response.message);
                      toastr.success(response.message, "", {
                        "toastClass": "toastr-success"
                      });
                      fetch_cart_nav_count(); //call by js file
                    }
                  }

                });
              });

            }
          } else if (response.status === 'error') {
            $('.no_record_class').show();
          }
        }
      });

      function fetchReviews() {
        //Fetch REview from db to display
        var productid = $('.product_id').val();
        $.ajax({
          url: '{{ route("review_fetch", ["id" => ":routeid"]) }}'.replace(':routeid', productid),
          success: function(response) {
            if (response.status === 'success') {
              var reviews = response.message; // Assuming response.reviews is an array of reviews
              if (reviews.length === 0) {
                $('.review_content').html('<p>No reviews yet.</p>');
              } else {
                // Clear any existing reviews
                $('.review_content').html('');
                // Loop through and append reviews
                reviews.forEach(function(review) {
                  var reviewHTML = `
                                <p class="rating_box d-inline">${review.rating} <i class="fas fa-star"></i></p>
                                <p class="d-inline">${review.review}</p>
                                <p><strong>${review.user.name}</strong> - <span>${review.updated_at.substring(0, 10)}</span>                          
                        `;
                  $('.review_content').append(reviewHTML);
                });
              }

            } else if (response.status === 'error') {
              $('.review_content').html('<h6>' + response.message + '</h6>');

            }
          }
        });
      }

    });
  </script>
</body>

</html>