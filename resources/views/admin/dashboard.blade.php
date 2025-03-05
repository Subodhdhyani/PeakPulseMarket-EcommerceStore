<x-dashboard_common>

  <x-slot:dynamic_title_top>
   Dashboard - PeakPulseMarket
    </x-slot>

    <x-slot:section_dynamic_content>
      <style>
        .containerr {
          display: grid;
          grid-template-columns: repeat(3, 1fr);
          grid-template-rows: auto auto;
          row-gap: 30px;
          column-gap: 15px
        }

        .box {
          max-height: 25vh;
          height: auto;
          background-color: thistle;
          display: flex;
          flex-direction: column;
          justify-content: flex-end;
          text-align: center;
          color: white;
        }

        .innerbox {
          max-height: 30px;
          height: auto;
          width: 100%;
          background-color: lightblue;
        }

        .anchor_style {
          text-decoration: none;
          color: grey;
          font-weight: bold;
          text-align: center;
        }

        .box:nth-child(1) {
          background-color: darkcyan;
        }

        .box:nth-child(2) {
          background-color: blue;
        }

        .box:nth-child(3) {
          background-color: purple;
        }

        .box:nth-child(4) {
          background-color: green;
        }

        .box:nth-child(5) {
          background-color: red;
        }

        .box:nth-child(6) {
          background-color: lightslategrey;
        }

        .box:nth-child(7) {
          background-color: chartreuse;
        }

        .box:nth-child(8) {
          background-color: dodgerblue;
        }

        .box:nth-child(9) {
          background-color: darkkhaki;
        }

        .box:nth-child(10) {
          background-color: darksalmon;
        }

        .box:nth-child(11) {
          background-color: dimgray;
        }

        .box:nth-child(12) {
          background-color: darkcyan;
        }

        .box:nth-child(13) {
          background-color: blue;
        }

        .box:nth-child(14) {
          background-color: chartreuse;
        }

        /* Media query for medium screens */
        @media (max-width: 968px) {
          .containerr {
            grid-template-columns: repeat(2, 1fr);
          }
        }
      </style>


      <h1 class="text-center">Dashboard</h1>
      <div class="d-flex justify-content-end align-items-center mt-2 mb-2">
        <a href="{{route('graph_dashboard')}}" style="text-decoration: none; font-family: 'Dancing Script', cursive; font-weight: bold; border: 2px solid currentColor; border-radius: 20px; padding: 5px 15px; display: inline-block;">
          <h5 class="text-danger" style="margin: 0;">Graphical Dashboard</h5>
        </a>
      </div>
      <div class="containerr">
        <div class="box">
          <h1 id="category">Loading...</h1>
          <div>Listed Category</div>
          <a class="anchor_style" href="{{route('category')}}">
            <div class="innerbox">Full Detail&ensp;<i class="fa-solid fa-arrow-right"></i></div>
          </a>
        </div>

        <div class="box">
          <h1 id="product">Loading...</h1>
          <div>Listed Products</div>
          <a class="anchor_style" href="{{route('product')}}">
            <div class="innerbox">Full Detail&ensp;<i class="fa-solid fa-arrow-right"></i></div>
          </a>
        </div>

        <div class="box">
          <h1 id="new">Loading...</h1>
          <div>New order</div>
          <a class="anchor_style" href="{{route('newbooking')}}">
            <div class="innerbox">Full Detail&ensp;<i class="fa-solid fa-arrow-right"></i></div>
          </a>
        </div>

        <div class="box">
          <h1 id="preparing">Loading...</h1>
          <div>Preparing Order</div>
          <a class="anchor_style" href="{{route('approved_preparing')}}">
            <div class="innerbox">Full Detail&ensp;<i class="fa-solid fa-arrow-right"></i></div>
          </a>
        </div>

        <div class="box">
          <h1 id="dispatched">Loading...</h1>
          <div>Dispatched Order</div>
          <a class="anchor_style" href="{{route('approved_dispatched')}}">
            <div class="innerbox">Full Detail&ensp;<i class="fa-solid fa-arrow-right"></i></div>
          </a>
        </div>

        <div class="box">
          <h1 id="delivered">Loading...</h1>
          <div>Delivered Order</div>
          <a class="anchor_style" href="{{route('approved_delivered')}}">
            <div class="innerbox">Full Detail&ensp;<i class="fa-solid fa-arrow-right"></i></div>
          </a>
        </div>

        <div class="box">
          <h1 id="cancel">Loading...</h1>
          <div>Cancelled Booking</div>
          <a class="anchor_style" href="{{route('cancel_booking')}}">
            <div class="innerbox">Full Detail&ensp;<i class="fa-solid fa-arrow-right"></i></div>
          </a>
        </div>

        <div class="box">
          <h1 id="return">Loading...</h1>
          <div>Returning Order</div>
          <a class="anchor_style" href="{{route('return')}}">
            <div class="innerbox">Full Detail&ensp;<i class="fa-solid fa-arrow-right"></i></div>
          </a>
        </div>

        <div class="box">
          <h1 id="refunded">Loading...</h1>
          <div>Refunded Order</div>
          <a class="anchor_style" href="{{route('refunded')}}">
            <div class="innerbox">Full Detail&ensp;<i class="fa-solid fa-arrow-right"></i></div>
          </a>
        </div>

        <div class="box">
          <h1 id="payment_failed">Loading...</h1>
          <div>Payment Failed</div>
          <a class="anchor_style" href="{{route('payment_failed_booking')}}">
            <div class="innerbox">Full Detail&ensp;<i class="fa-solid fa-arrow-right"></i></div>
          </a>
        </div>

        <div class="box">
          <h1 id="user">Loading...</h1>
          <div>Registered User</div>
          <a class="anchor_style" href="{{route('user')}}">
            <div class="innerbox">Full Detail&ensp;<i class="fa-solid fa-arrow-right"></i></div>
          </a>
        </div>

        <div class="box">
          <h1 id="manage_review">Loading...</h1>
          <div>Product Reviews</div>
          <a class="anchor_style" href="{{route('manage_review')}}">
            <div class="innerbox">Full Detail&ensp;<i class="fa-solid fa-arrow-right"></i></div>
          </a>
        </div>

        <div class="box">
          <h1 id="manage_complain">Loading...</h1>
          <div>Product Complains</div>
          <a class="anchor_style" href="{{route('complains')}}">
            <div class="innerbox">Full Detail&ensp;<i class="fa-solid fa-arrow-right"></i></div>
          </a>
        </div>

        <div class="box">
          <h1 id="add_notification">Loading...</h1>
          <div>Add Notification</div>
          <a class="anchor_style" href="{{route('notification_form')}}">
            <div class="innerbox">Full Detail&ensp;<i class="fa-solid fa-arrow-right"></i></div>
          </a>
        </div>


      </div>
      <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
      {{--Show Spinner during page loading--}}
      <script src="{{url('js/spinner.js')}}"></script>
      <script>
        $(document).ready(function() {
          function fetchdata() {
            $.ajax({
              url: '{{ route("dashboard_fetch") }}',
              type: 'GET',
              success: function(data) {
                $('#category').text(data.category);
                $('#product').text(data.product);
                $('#new').text(data.new);
                $('#preparing').text(data.preparing);
                $('#dispatched').text(data.dispatched);
                $('#delivered').text(data.delivered);
                $('#cancel').text(data.cancel);
                $('#return').text(data.return);
                $('#refunded').text(data.refunded);
                $('#payment_failed').text(data.payment_failed);
                $('#user').text(data.user);
                $('#manage_review').text(data.manage_review);
                $('#manage_complain').text(data.manage_complain);
                $('#add_notification').text(data.add_notification);

              }
            });

          }
          fetchdata();
        });
      </script>






      </x-slot>
</x-dashboard_common>