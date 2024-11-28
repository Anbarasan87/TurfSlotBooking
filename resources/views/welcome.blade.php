<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turf Slot Booking</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.cyan-light_blue.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <style>
        body {
            background-color: #f6f7fa;
        }
        .mdl-layout__header {
            background-color: #0e1a35; 
            color: #ffffff; 
        }
        .container1 {
            text-align: center;
            margin-top: 200px; 
            color: #ffffff; 
            padding: 20px; 
            border-radius: 10px; 
        }
        .cta-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.5);
        }
        .modal-body {
    overflow-y: auto;
}

    </style>
</head>
<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Turf Slot Booking</span>
                <div class="mdl-layout-spacer"></div>
                <!-- <div class="top-right">
                <a class="btn btn-primary btn-custom" data-toggle="modal" data-target="#loginModal">Login</a>
                <button class="btn btn-success btn-custom" data-toggle="modal" data-target="#registerModal">Register</button>
                </div> -->
            </div>
        </header>
    </div>   
    <section id="home" class="about section" style="background-color: #f0f8ff; padding: 170px 0 120px 0; background-image: url('{{ asset('storage/images/bg-.jpeg') }}'); background-size: cover; background-position: center; min-height: 500px;width:100%">
        <div class="container1">
            <div class="col-lg-8 col-md-10 d-flex flex-column justify-content-center text-center">
                <h2 data-aos="fade-up" style="font-size: 2.75rem; font-weight: 700; color: #333;">Discover the Perfect Turf for Your Game
                </h2>
                <p style="font-size: 1.3rem; color: #ffffff; margin-bottom: 30px;">
                    We offer premium turfs with excellent facilities, ensuring an exceptional experience for every game.
                </p>
                <div class="d-flex justify-content-center">
                    <button class="cta-btn btn-get-started" data-toggle="modal" data-target="#loginModal" 
                            style="padding: 12px 25px; font-size: 1.2rem; background: rgba(14, 26, 53, 0.6); 
                                   color: white; border-radius: 30px; text-decoration: none; 
                                   backdrop-filter: blur(10px); box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); 
                                   transition: transform 0.3s, box-shadow 0.3s;">
                        Let's Start
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="container">    
        @include('Auth.login')
        @include('Auth.register')
    </div>
 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</body>
</html>
