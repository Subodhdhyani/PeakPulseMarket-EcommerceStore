  <div class="container-fluid bg-warning">
      <footer class="py-5">
          <div class="row">
              <div class="col-6 col-md-2 mb-3">
                  <h5>About</h5>
                  <ul class="nav flex-column">
                      <li class="nav-item mb-2"><a href="{{route('aboutus')}}" class="nav-link p-0 text-muted">About Us</a></li>
                      <li class="nav-item mb-2"><a href="{{route('contactus')}}" class="nav-link p-0 text-muted">Contacts Us</a></li>
                      <li class="nav-item mb-2"><a href="{{route('careers')}}" class="nav-link p-0 text-muted">Careers</a></li>
                      <li class="nav-item mb-2"><a href="{{route('ourstores')}}" class="nav-link p-0 text-muted">Our Stores</a></li>
                      <li class="nav-item mb-2"><a href="{{route('sellwithus')}}" class="nav-link p-0 text-muted">Sell with us</a></li>
                  </ul>
              </div>

              <div class="col-6 col-md-3 mb-3">
                  <h5>Help</h5>
                  <ul class="nav flex-column">
                      <li class="nav-item mb-2"><a href="{{route('payment')}}" class="nav-link p-0 text-muted">Payment</a></li>
                      <li class="nav-item mb-2"><a href="{{route('shipping')}}" class="nav-link p-0 text-muted">Shipping</a></li>
                      <li class="nav-item mb-2"><a href="{{route('cancellation')}}" class="nav-link p-0 text-muted">Cancellation & Return</a></li>
                      <li class="nav-item mb-2"><a href="{{route('faq')}}" class="nav-link p-0 text-muted">FAQs</a></li>
                      <li class="nav-item mb-2"><a href="{{route('privacy')}}" class="nav-link p-0 text-muted">Privacy</a></li>
                  </ul>
              </div>

              <div class="col-md-4 mb-3">
                  <div class="d-flex flex-column flex-md-row justify-content-between">
                      <div>
                          <h5>Registered Office & Mail us</h5>
                          <p>Peak Pulse Internet Private Limited,<br>
                              Himalayan Tower, IT Park,<br>
                              Sahastradhara Rd, Dehradun,<br>
                              Dehradun, 248001,<br>
                              Uttarakhand, India</p>
                          <h6>Social:</h6>
                          <a class="link-dark" href="https://www.facebook.com/" target="_blank"><i class="fa-brands fa-facebook fa-xl"></i></a>
                          <a class="link-dark" href="https://www.instagram.com/" target="_blank"><i class="fa-brands fa-square-instagram fa-xl"></i></a>
                          <a class="link-dark" href="https://twitter.com/" target="_blank"><i class="fa-brands fa-square-x-twitter fa-xl"></i></a>
                      </div>
                  </div>
              </div>

              <div class="col-md-3 mb-3 mt-md-0 mt-4">
                  <div class="d-flex flex-column flex-md-row justify-content-between">
                      <div>
                          <h5>Contact Us</h5>
                          <p><i class="fa-solid fa-location-dot"></i>&ensp;Dehradun,Uttarakhand</p>
                          <p><i class="fa-solid fa-phone"></i>&ensp;<span id="contact-mobile">1111111111</span></p>
                          <p><i class="fa-solid fa-clock"></i>&ensp;24*7 Available</p>
                          <p><i class="fa-regular fa-envelope"></i>&nbsp;<span id="contact-email">info@peakpulsemarket.com</span></p>

                          <h6 class="border-bottom border-dark">Download the Apps</h6>
                          <a href="https://play.google.com/store/apps" target="_blank"><img src="{{asset('appstore-logo/google-play.png')}}" alt="playstore"></a>&nbsp;
                          <a href="https://apps.apple.com/" target="_blank"><img src="{{asset('appstore-logo/apple-store.png')}}" alt="applestore"></a>


                      </div>
                  </div>
              </div>
          </div>

          <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top border-dark">
              <p><strong>© Jan 2025-{{ date("Y") }} Peak Pulse Market. All rights reserved.</strong></p>
              <img src="{{asset('payment/payments-methods.svg')}}" alt="Payment Methods">
          </div>
      </footer>
  </div>
  <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
  <script>
      $(document).ready(function() {
          //For Contact and email Fetch from db and append to footer
          $.ajax({
              url: '{{ route("footer_fetch") }}',
              type: 'GET',
              success: function(response) {
                  if (response.status === 'success') {
                      var email = response.email;
                      var mobile = response.mobile;
                      // Update email and mobile in the footer
                      $('#contact-email').text(email);
                      $('#contact-mobile').text(mobile);
                  }
              }
          });
      });
  </script>