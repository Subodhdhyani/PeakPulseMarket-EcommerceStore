<x-dashboard_common>
     <x-slot:dynamic_title_top>
          Graphical Dashboard - PeakPulseMarket
          </x-slot>
          <x-slot:section_dynamic_content>
               <style>
                    .containerr {
                         display: grid;
                         grid-template-columns: repeat(2, 1fr);
                         grid-template-rows: auto auto;
                         row-gap: 30px;
                         column-gap: 15px
                    }

                    .box {
                         max-height: 10vh;
                         height: auto;
                         display: flex;
                         flex-direction: column;
                         justify-content: flex-end;
                         text-align: center;
                    }

                    .box a {
                         text-decoration: none;
                         font-family: 'Dancing Script', cursive;
                         font-weight: bold;
                         border: 2px solid currentColor;
                         border-radius: 20px;
                         padding: 5px 15px;
                         display: inline-block;
                    }

                    .box h5 {
                         color: red;
                    }

                    /* For Small Screens */
                    @media screen and (max-width: 600px) {
                         #bookingChart {
                              height: 300px !important;
                         }
                    }
               </style>
               <span>
                    <a href="javascript:void(0);"
                         style="text-decoration: none; font-family: 'Dancing Script', cursive; font-weight: bold; border: 2px solid currentColor; border-radius: 20px; padding: 5px 15px; display: inline-block;"
                         class="text-danger"
                         title="Go Back"
                         onclick="history.back()">
                         Go Back
                    </a>
               </span>
               <h1 class="text-center mb-4">Graphical Dashboard</h1>

               <div class="containerr">
                    <div class="box">
                         <a href="{{route('productGraph')}}">
                              <h5>Product's Statistics</h5>
                         </a>
                    </div>

                    <div class="box">
                         <a href="{{route('userGraph')}}">
                              <h5>User's Statistics</h5>
                         </a>
                    </div>

                    <div class="box">
                         <a href="{{route('bookingGraph')}}">
                              <h5>Sale's Statistics</h5>
                         </a>
                    </div>

                    <div class="box">
                         <a href="{{route('helpGraph')}}">
                              <h5>Complain's Statistics</h5>
                         </a>
                    </div>

                    <div class="box">
                         <a href="{{route('trackGraph')}}">
                              <h5>Tracking's Statistics</h5>
                         </a>
                    </div>

                    <div class="box">
                         <a href="{{route('returnGraph')}}">
                              <h5>Order's Return Statistics</h5>
                         </a>
                    </div>

                    <div class="box">
                         <a href="{{route('otherGraph')}}">
                              <h5>Other Statistics</h5>
                         </a>
                    </div>
               </div>
               {{--Show Spinner during page loading--}}
               <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
               <script src="{{url('js/spinner.js')}}"></script>
               </x-slot>
</x-dashboard_common>