<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .business-logo {
    height: 65px;
    width: 90px;
  }

  @media (max-width: 768px) {
    .business-logo {
      height: 45px;
      width: 70px;
    }
  }

  .notification-popup {
    position: absolute;
    top: 60px;
    /* Adjusted so it doesn't cover the navbar */
    right: 20px;
    /* Position on the right side of the screen */
    width: 320px;
    background-color: #ffffff;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 1050;
    overflow: hidden;
    font-family: Arial, sans-serif;
    display: none;
  }

  .notification-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    background-color: #f7f7f7;
    border-bottom: 1px solid #ddd;
    font-weight: bold;
  }

  .notification-body {
    padding: 10px 15px;
    max-height: 300px;
    overflow-y: auto;
    /* Allows scrolling if there are many notifications */
  }

  .notification-body ul {
    padding-left: 20px;
  }

  .notification-body li {
    margin-bottom: 10px;
  }

  .btn-close {
    border: none;
    background: none;
    font-size: 1.2rem;
    cursor: pointer;
  }

  .search_container {
    background-color: white;
    max-height: 30vh;
    height: auto;
    border: 1px solid grey;
    border-radius: 5px;
    box-sizing: border-box;
    /* Includes padding and border in the width/height */
    overflow: hidden;
    /* Prevents content from overflowing */
    margin: 5px;
  }

  .search_container:hover {
    box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.75);
    /*  box shadow on hover */
  }

  .search_hide_anchor_style {
    text-decoration: none;
    color: inherit;
  }

  .search_first_section {
    display: flex;
    align-items: center;
    justify-content: center;
    /* Center the image */
    overflow: hidden;
    height: 100%;
    /* the container takes full height */
  }

  .search_first_section img {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
  }

  .rating-container {
    display: flex;
    flex-direction: row;
    align-items: center;
  }

  .rating-value {
    background-color: #4caf50;
    color: #fff;
    padding: 2px 5px;
    border-radius: 5px;
    font-size: 12px;
  }

  .dynamic-search-result {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    /* Adjusts based on screen size */
    gap: 10px;
    /* Spacing between the cards */
    min-height: 400px;
  }

  .pagination-container {
    grid-column: 1 / -1;
    /* Makes pagination span across the entire grid */
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    margin-top: 20px;
    padding-bottom: 10px;
    /* Adds space between the pagination and the bottom of the container */
  }

  .dropdown-menu .dropdown-item:active {
    background-color: red;
    /* Replace with your preferred color */
    color: white;
    /* Text color when clicked */
  }

  .navbar-spinner-container {
    display: flex;
    justify-content: center;
    /* Centers the spinner horizontally */
    align-items: center;
    /* Centers the spinner vertically */
    height: 100%;
    /* it takes full height of the parent */
    width: 100%;
    /* it takes full width of the parent */
  }

  .navbar-spinner {
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-top: 4px solid red;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
</style>

<div>
  <nav class="navbar navbar-expand-lg bg-warning">
    <div class="container-fluid d-flex align-items-center">
      <a class="navbar-brand me-4" href="{{route('index')}}">
        <img class="business-logo" src="{{asset('Business_Logo/logo1.png')}}" alt="Business Logo">
      </a>
      <form class="d-none d-md-flex flex-grow-1 me-4" role="search" id="nav_search" autocomplete="off">
        <input class="form-control me-2 flex-grow-1" type="search" placeholder="Enter to Search" id="search" value="{{old('search')}}" name="search" aria-label="Search" style="max-width: 600px;">
        <button class="btn btn-outline-warning bg-light" id="search_submit" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
      </form>
      @auth('custom_web')
      <div>
        <h6 id="greetingnameatnavbar" class="d-none d-md-block text-dark"></h6>
      </div> {{--Show Welcome name at navbar by dynamically fetch--}}
      @endauth

      <div class="ms-auto d-flex align-items-center text-dark">
        @guest('custom_web')
        <a class="nav-link me-4" href="{{route('signin') }}"><i class="fa-solid fa-user"></i>&nbsp;Login</a>
        @endguest
        @auth('custom_web')
        <div class="dropdown me-3">
          <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('Business_Logo/logo1.png') }}" alt="Profile Image" id="navimagefetch" class="rounded-circle" style="height: 40px; width: 40px;">
          </a>
          <ul class="dropdown-menu dropdown-menu-end bg-warning" aria-labelledby="profileDropdown">
            <li><a class="dropdown-item" href="{{route('manage_profile')}}"><i class="fa-solid fa-user"></i>&nbsp;Profile</a></li>
            <li><a class="dropdown-item" href="{{route('myorder')}}"><i class="fa-solid fa-cart-arrow-down"></i>&nbsp;My Order</a></li>
            <li>
              <a class="dropdown-item" href="{{route('signout')}}"><i class="fa-solid fa-right-from-bracket"></i>&nbsp;Logout</a>
            </li>
          </ul>
        </div>
        <a class="nav-link position-relative" href="#" id="notificationBell"><i class="fa-solid fa-bell"></i></a>&ensp;
        @endauth

        <span class="text-light" style="font-size: 1.7em;" id="cart-count"><sup>0</sup></span>
        <a class="nav-link" href="{{route('cart')}}"><i class="fa-solid fa-cart-shopping"></i>&nbsp;Cart</a>
      </div>
    </div>
  </nav>
</div>

<!-- Notification Pop-up -->
<div id="notificationPopup" class="notification-popup" style="display: none;">
  <div class="notification-header">
    <h6>Notifications</h6>
    <button type="button" class="btn-close" id="closeNotificationPopup">×</button>
  </div>
  <div class="notification-body">
    <ul id="notificationList">
      <!-- Notification items will be inserted here dynamically -->
    </ul>
  </div>
</div>

<!-- Dynamic Search Result Section -->
<div class="dynamic-search-result mt-2 mb-2" style="display: none;"></div>

<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
{{--cart item counts at navbar--}}
<script src="{{ asset('js/nav_cart_count.js') }}"></script>
<script>
  $(document).ready(function() {
    // Show or hide the notification pop-up when clicking the bell
    $('#notificationBell').on('click', function(e) {
      e.preventDefault();
      $('#notificationPopup').toggle(); // Toggle visibility

      if ($('#notificationPopup').is(':visible')) {
        // Fetch notifications only when the popup is visible
        $.ajax({
          url: '{{ route("fetch_notifications") }}',
          type: 'GET',
          success: function(response) {
            if (response.status === 'success') {
              var notifications = response.notifications;
              var notificationList = $('#notificationList');
              notificationList.empty(); // Clear old notifications

              if (notifications.length > 0) {
                // Loop through each notification in the array
                notifications.forEach(function(notification) {
                  // Construct notification content dynamically
                  var notificationItem = `
                                    <li style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 5px; background-color:white;list-style-type: none;">
                                        <strong>${notification.title}</strong><br>
                                        ${notification.message}
                                        <!-- Display image if exists -->
                                        ${notification.image ? `<br><img src="{{ asset('storage/notification_image') }}/${notification.image}" alt="Notification Image" style="width: 100%; height: 150px;">` : ''}
                                    </li>
                                `;
                  notificationList.append(notificationItem);
                });
              } else {
                // Display a message if no notifications are available
                notificationList.append('<li>No new notifications.</li>');
              }
            }
          },
          error: function() {
            // Handle error fetching notifications
            $('#notificationList').html('<li>Error fetching notifications.</li>');
          }
        });
      }
    });

    // Close the pop-up when clicking the close button
    $('#closeNotificationPopup').on('click', function() {
      $('#notificationPopup').fadeOut();
    });

    // Close the pop-up when clicking anywhere outside
    $(document).on('click', function(e) {
      if (!$(e.target).closest('#notificationPopup').length && !$(e.target).closest('#notificationBell').length) {
        $('#notificationPopup').fadeOut();
      }
    });

    // Fetch cart data for the count
    /*Now i create these separte js file and call where it used
    function fetch_cart_nav_count() {
      $.ajax({
        url: '{{ route("fetch_cart_nav_count") }}',
        type: 'GET',
        success: function(response) {
          if (response.status === 'success') {
            var totalQuantity = response.total_quantity;
            $('#cart-count').find('sup').text(totalQuantity);
          }
        }
      });
    }*/
    fetch_cart_nav_count(); //call by js file


    // Display profile picture and greeting name at navbar
    $.ajax({
      url: '{{ route("user_profile_fetch_nav") }}',
      type: 'GET',
      success: function(response) {
        if (response.data) {
          // User is logged in, display their name and profile picture
          $('#greetingnameatnavbar').html('Welcome ' + response.data.name.substring(0, 7).toUpperCase() + '&emsp;');

          //var imageUrl = "{{ asset('storage/user_profile/') }}" + '/' + response.data.profile_pic;
          //$('#navimagefetch').attr('src', imageUrl);
          // Check if profile_pic exists in the database
          var imageUrl = response.data.profile_pic ?
            "{{ asset('storage/user_profile/') }}" + '/' + response.data.profile_pic :
            "{{ asset('Business_Logo/logo1.png') }}"; // Default image path

          // Update the image if profile_pic exists
          $('#navimagefetch').attr('src', imageUrl);
        }

      }
    });

    //Show search result
    $('#search_submit').click(function(e) {
      e.preventDefault();
      var search = $('#search').val();
      if (search) {
        $('.dynamic-search-result').show();
        $('.dynamic-search-result-show-then-hide-this').hide();
        //$('.dynamic-search-result').html('<p>Loading...</p>');
        //$('.dynamic-search-result').html('<div class="spinner-border text-danger" role="status"><span class="visually-hidden">Loading...</span></div>');
        $('.dynamic-search-result').html('<div class="navbar-spinner-container"><div class="navbar-spinner"></div></div>');
        // Hide existing content
        //$('#mainContent').hide();
        //$('.no_record_class').hide();

        // Perform the search via AJAX

        $.ajax({
          url: '{{ route("search") }}',
          type: 'POST',
          data: {
            search: search
          },
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.status === 'success') {
              renderResults(response.message, response.pagination);
            } else if (response.status === 'error') {
              $('.dynamic-search-result').html(`
                  <div style="display: flex; justify-content: center; align-items: center; height: 100%; min-height: 200px;">
                     <h2 style="text-align: center; color: red;">No Searched Results Found !</h2>
                  </div>
               `);
            }
          },

        });
      }
    });


    function renderResults(products, pagination) {
      var html = '';
      // Loop through each product and generate the HTML
      products.forEach(function(record) {
        var productUrl = `{{ url('product_checkout') }}/${record.id}`;
        var resultsHtml = `
            <div class="search_container mt-2 mb-2">
                <div class="row h-100">
                    <div class="col-6 search_first_section">
                        <a href="${productUrl}" class="search_hide_anchor_style">
                            <img src="{{ asset('storage/product_image/') }}/${record.product_image}" alt="product_image" class="img-fluid">
                        </a>
                    </div>
                    <div class="col-6 search_second_section">
                        <h5 class="mt-4">
                            <a href="${productUrl}" class="search_hide_anchor_style">
                                ${record.product_name}
                            </a>
                        </h5>
                        <div class="rating-container mb-2">
                            <div class="rating-value">${record.average_rating} &#9733;</div>
                        </div>
                        <h6>&#8377;${record.sale_price} 
                            <del>&#8377;${record.original_price}</del>
                            <span class="text-success">  ${record.discount}% off</span>
                        </h6>
                    </div>
                </div>
            </div>
        `;
        html += resultsHtml;
      });

      // Insert the search results into the container
      $('.dynamic-search-result').html(html);

      // Add pagination at the bottom, if multiple data
      if (pagination) {
        var paginationHtml = `
            <nav class="pagination-container">
                <ul class="pagination justify-content-center">
                    ${pagination.prev_page_url ? `<li class="page-item"><a class="page-link" href="#" data-url="${pagination.prev_page_url}">Previous</a></li>` : ''}
                    <li class="page-item active"><a class="page-link">${pagination.current_page}</a></li>
                    ${pagination.next_page_url ? `<li class="page-item"><a class="page-link" href="#" data-url="${pagination.next_page_url}">Next</a></li>` : ''}
                </ul>
            </nav>
        `;
        // Append the pagination to the bottom of the search results container
        $('.dynamic-search-result').append(paginationHtml);
      }
    }

    // Handle Pagination Links
    $(document).on('click', '.pagination a', function(e) {
      e.preventDefault();
      var url = $(this).data('url');
      if (url) {
        $.ajax({
          url: url,
          type: 'GET',
          success: function(response) {
            if (response.status === 'success') {
              renderResults(response.message, response.pagination);
            }
          },
          error: function(xhr) {
            console.error('Error:', xhr.responseText);
          }
        });
      }
    });


  });
</script>