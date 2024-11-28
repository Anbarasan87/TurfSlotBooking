<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>User Page - Turf slot Booking</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com/css2?family=Roboto:wght@100;400;700&display=swap" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body style="font-family: 'Roboto', sans-serif; color: #333; margin-top: 15%; background-color: #f9f9f9; margin: 0;">
@if(session('payment_success'))
    <div class="modal fade" id="paymentSuccessModal" tabindex="-1" role="dialog" aria-labelledby="paymentSuccessLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #0e1a35; color: white;">
                    <h5 class="modal-title" id="paymentSuccessLabel">Payment Successful</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ session('payment_success') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#paymentSuccessModal').modal('show');
        });
    </script>
@endif
 
<header id="header" style="background-color: #fff; position: fixed; width: 100%; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);  z-index: 10;">
  <div style="display: flex; align-items: center; position: relative;">
    <img src="{{ asset('storage/images/favicon.png') }}" alt="Favicon" style="width: 3%; height: 3%;">
    <img src="{{ asset('storage/images/logo.png') }}" alt="Logo" style="width: 5%; height: 5%; position: relative; top: 5px; left: -5px;">
  </div>  
  <div class="topbar" style="background: #0e1a35; color: #fff; font-size: 14px;">
      <div class="container" style="display: flex; justify-content: center; align-items: center; padding: 5px 0;">
        <div class="contact-info" style="display: flex; align-items: center; gap: 15px;">
          <i class="bi bi-envelope"><a href="mailto:user1@example.com" style="color: #fff;">user1@example.com</a></i>
          <i class="bi bi-phone"><span style="color: #fff;">+91 9876543210</span></i>
        </div>
      </div>
    </div>

    <div class="container" style="display: flex; align-items: center; justify-content: space-between; padding: 0 20px;">
      <a href="{{ url('/') }}" class="logo" style="display: flex; align-items: center; text-decoration: none; margin-right: 20px;">
        <h1 class="sitename" style="font-weight: 700; color: #0e1a35;">Turf Slot Booking</h1>
      </a>

      <nav id="navmenu" style="display: flex; align-items: center; margin-right: 20px;">
        <ul style="list-style: none; display: flex; gap: 20px; padding: 0; margin: 0;">
          <li><a href="#home" style="color: #333; text-decoration: none; font-weight: 500;">Home</a></li>
          <li><a href="#about" style="color: #333; text-decoration: none; font-weight: 500;">About</a></li>
          <li><a href="#turf" style="color: #333; text-decoration: none; font-weight: 500;">Turfs</a></li>
          <li><a href="#contact" style="color: #333; text-decoration: none; font-weight: 500;">Contact</a></li>
        </ul>
      </nav>
     
      <a class="book-a-table" href="#turf" style="background-color: #0e1a35; color: #fff; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin-left: 20px;">Book a Turf</a>
      <div class="user-dropdown" style="position: relative; margin-left: 20px;">
        <a href="#" onclick="toggleDropdown()" style="text-decoration: none; color: #333;">
          <img src="{{ asset('storage/images/user.png') }}" alt="User" style="width: 30px; height: 30px; border-radius: 50%;">
        </a>
        <div class="dropdown-menu" id="userDropdownMenu" style="display: none; position: absolute; top: 100%; right: 0; background-color: #fff; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); border-radius: 5px; min-width: 200px; overflow: hidden;">
        <a href="{{ route('dashboard.user', ['view' => 'profile']) }}" style="display: block; padding: 10px 15px; color: #333; text-decoration: none;">My Profile</a>
        <a href="{{ route('dashboard.user', ['view' => 'bookings']) }}" style="display: block; padding: 10px 15px; color: #333; text-decoration: none;">My Bookings</a>
        <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
          <a href="#" onclick="document.getElementById('logoutForm').submit();" style="display: block; padding: 10px 15px; color: #333; text-decoration: none;">Logout</a>
        </div>
      </div>
    </div>
  </header>
  <script>
    function toggleDropdown() {
      var dropdownMenu = document.getElementById('userDropdownMenu');
      dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'block' : 'none';
    }

    window.addEventListener('click', function(event) {
      var dropdown = document.getElementById('userDropdownMenu');
      if (!event.target.closest('.user-dropdown')) {
        dropdown.style.display = 'none';
      }
    });
  </script>

  <style>
    .user-dropdown:hover .dropdown-menu {
      display: block;
    }

    .dropdown-menu a:hover {
      background-color: #0e1a35;
      color: #fff;
    }
  </style>
  <div style="height: 24vh;"></div>
<section id="home" class="about section" style="background-color: #f0f8ff; padding: 170px 0 120px 0; background-image: url('{{ asset('storage/images/bg-.jpeg') }}'); background-size: cover; background-position: center; min-height: 500px;">
  <div class="container">
    <div class="row gy-4 justify-content-center">
      <div class="col-lg-8 col-md-10 d-flex flex-column justify-content-center text-center">
        <h2 data-aos="fade-up" style="font-size: 2.75rem; font-weight: 700; color: #333;">
          Discover the Perfect Turf for Your Game
        </h2>
        <p data-aos="fade-up" data-aos-delay="100" style="font-size: 1.3rem; color: white;">
          We offer premium turfs with excellent facilities, ensuring an exceptional experience for every game.
        </p>
        <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="200">
          <a href="#turf" class="cta-btn btn-get-started" style="padding: 12px 25px; font-size: 1.2rem; background-color: #0e1a35; color: white; border-radius: 5px;">
            Book a Turf
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about" class="about section" style="width: 100%; min-height: 100vh; padding: 80px 0; display: flex; align-items: center; justify-content: center; background: #f0f8ff; position: relative; border: 5px solid #1c313a;">
  <div class="container" style="position: relative; z-index: 3; max-width: 1200px;">
    <div class="row gy-4 justify-content-center align-items-center" style="display: flex; flex-wrap: wrap; justify-content: space-between;">
      <div class="d-flex flex-column justify-content-center" style="color: #333; background: #ffffff; padding: 40px; flex: 1; max-width: 50%; box-sizing: border-box;">
        <h2 style="margin: 0; font-size: 36px; font-weight: 700; color: #1c313a;">
          Discover Our Premium Turf Facilities
        </h2>
        <p style="margin: 10px 0 0 0; font-size: 18px; color: #666;">
          Our turfs are designed to provide the best possible playing experience with top-notch facilities and easy booking options.
        </p>  
        <div style="margin-top: 30px;">
          <ul style="list-style: disc; padding-left: 20px;">
            <li style="font-size: 16px; color: #666;">Top-quality grass and playing surfaces for an excellent game.</li>
            <li style="font-size: 16px; color: #666;">Flexible booking options for your convenience.</li>
            <li style="font-size: 16px; color: #666;">Premium amenities for players and spectators.</li>
            <li style="font-size: 16px; color: #666;">Easy access and great location for all users.</li>
          </ul>
        </div>
        <div style="display: flex; margin-top: 20px;">
          <a href="#turf" style="background-color: #1c313a; color: #fff; padding: 12px 25px; border-radius: 5px; text-decoration: none; font-size: 16px; font-weight: 600; transition: background-color 0.3s ease; margin-right: 10px;">
            Book a Turf
          </a>
          <a href="#turf" style="background-color: #1c313a; color: #fff; padding: 12px 25px; border-radius: 5px; text-decoration: none; font-size: 16px; font-weight: 600; display: flex; align-items: center; transition: background-color 0.3s ease;">
            <i class="bi bi-play-circle" style="margin-right: 5px;"></i>
            View Turf Gallery
          </a>
        </div>
  </div>
      <div class="d-flex justify-content-end" style="flex: 1; max-width: 50%; box-sizing: border-box; padding: 20px; transition: 0.3s;">
        <img src="{{ asset('storage/images/new_turf.jpg') }}" alt="Turf Image" style="max-width: 100%; height: auto; border-radius: 8px; transition: 0.3s;">
      </div>
    </div>
  </div>
</section>
<section id="turf" class="menu" style="background-color: #f0f0f5; padding: 40px 0;">
    <div class="container section-title" style="text-align: center; padding: 20px;">
        <h2>Available Turfs</h2>
        <p style="font-weight: bold;">Check Our Turf Options</p>
    </div>

    <div class="container">
        <h2 style="text-align: center; margin-bottom: 30px;">Our Turfs</h2> 
        <div class="row" style="margin-top: 30px; display: flex; flex-wrap: wrap; gap: -250px;">
            @foreach($allTurfs as $turf)
                <div class="col-lg-4 col-md-4 col-sm-8 d-flex justify-content-center" style="margin-bottom: 20px;">
                    <div class="card" style="width: 100%; max-width: 100%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); border-radius: 8px; overflow: hidden; background: #fff;">
                        <a href="{{ route('dashboard.user', ['turf_id' => $turf->id]) }}" style="text-decoration: none;">
                            <img src="{{ asset('storage/images/' . $turf->image) }}" alt="{{ $turf->name }}" 
                                 style="width: 100%; height: 200px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                        </a>
                        <div class="card-body" style="padding: 15px;">
                            <h3 style="font-size: 1.2em; font-weight: bold; color: #333; margin: 0;">{{ $turf->name }}</h3>
                            <p style="color: rgba(0, 0, 0, 0.6); font-style: italic;">{{ $turf->sport_type }} - {{ $turf->location }}</p>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                            <span style="font-size: 1em; color: #333; font-weight: bold;">₹{{ number_format($turf->price_per_hour, 2) }} / hour</span>
                                <a href="{{ route('dashboard.user', ['turf_id' => $turf->id]) }}"  style="padding: 8px 12px; background-color: #28a745; color: white; border-radius: 4px; text-decoration: none; font-weight: bold;">Book Now - ₹{{ number_format($turf->price_per_hour, 2) }}
                                </a></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<footer id="contact" style="background-color: #0e1a35; color: #ddd; padding: 60px 0;">
  <div class="container">
    <div class="row" style="display: flex; justify-content: space-between;">
      
      <div class="footer-about" style="flex: 1; padding-left: 20px;">
        <a href="{{ url('/') }}" class="logo" style="font-size: 20px; color: #fff; text-decoration: none;">Turf Slot Booking</a>
        <div class="footer-contact" style="margin-top: 20px;">
          <p style="margin: 10px 0; color: #aaa;">A01 New Street, Erode, 638901</p>
          <p style="margin: 10px 0; color: #aaa;"><strong>Phone:</strong> +91 987654321</p>
          <p style="margin: 10px 0; color: #aaa;"><strong>Email:</strong> user1@example.com</p>
        </div>
      </div>

      <div class="footer-newsletter" style="flex: 1;">
        <h4 style="font-size: 18px; color: #fff; margin-bottom: 15px;">Our Newsletter</h4>
        <p style="color: #aaa;">Subscribe to receive the latest updates on available turfs and special offers.</p>
        <form action="{{ route('newsletter.subscribe') }}" method="post" style="display: flex; flex-direction: column;">
          @csrf
          <input type="email" name="email" placeholder="Your Email" 
            style="padding: 10px; width: 80%; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; font-size: 14px; color: #333;">
          <input type="submit" value="Subscribe" 
            style="padding: 8px 20px; background-color: #0e1a35; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
        </form>
      </div>

    </div>
  </div>
      </div>
    </div>
    <div class="container" style="text-align: center; margin-top: 20px;">
      <p style="font-size: 13px; color: #999;">© {{ now()->year }} <strong style="color: #0e1a35;">Turf Slot Booking</strong> All Rights Reserved</p>
    </div>
  </footer>

</body>
</html>
