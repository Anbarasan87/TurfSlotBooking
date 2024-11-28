<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.cyan-light_blue.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f6f7fa;
        }
        .mdl-layout__header {
            background-color: #0e1a35;
            color: #ffffff;
        }
        .mdl-navigation__link {
            color: #ffffff;
        }
        .mdl-layout__drawer {
            background-color: #1c313a;
        }
        .profile-card {
            margin-top: 40px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 40px auto;
        }
        .profile-card h4 {
            margin-bottom: 25px;
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }
        .profile-info {
            margin-top: 20px;
        }
        .profile-info p {
            font-size: 18px;
            line-height: 1.6;
            color: #666;
        }
        .profile-info p strong {
            color: #333;
        }
        .profile-info .btn {
            padding: 10px 20px;
            background-color: #0e1a35;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        .profile-info .btn:hover {
            background-color: #1c313a;
        }
        .profile-card .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
            object-fit: cover;
        }
        .profile-card .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .mdl-card {
            margin: 20px;
            cursor: pointer;
        }
        .mdl-card:hover {
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
        }
        .mdl-card__supporting-text {
            font-size: 2rem;
        }
    </style>
</head>
<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">User Profile</span>
                <div class="mdl-layout-spacer"></div>
                <form action="{{ route('logout') }}" method="POST" style="margin-left: 20px;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <header class="mdl-layout__drawer-header" style="padding: 16px; background-color: #1c313a; text-align: center;">
                <span style="color: #ffffff; font-weight: bold; font-size: 1.2rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6);">
                    {{ auth()->user()->name ?? 'Guest' }}
                </span>
            </header>
            <nav class="mdl-navigation">
                <a href="{{ route('dashboard.user', ['view' => 'profile']) }}" style="display: block; padding: 10px 15px; color: white; text-decoration: none;">My Profile</a>
                <a href="{{ route('dashboard.user', ['view' => 'bookings']) }}" style="display: block; padding: 10px 15px; color: white; text-decoration: none;">My Bookings</a>        
                <div class="text-center">
                                <a href="{{ route('dashboard.user') }}" class="btn btn-muted">Back to Dashboard</a>
                            </div>                  
            </nav>
        </div>
        <main class="mdl-layout__content mdl-color--grey-100">
            <div class="mdl-grid demo-content">
                <div class="mdl-cell mdl-cell--12-col">
                    <div class="profile-card">
                        <div class="profile-header">
                            <img src="https://icons-for-free.com/iff/png/512/avatar+human+people+profile+user+icon-1320168139431219590.png" alt="User Avatar" class="avatar">
                            <h4><strong>Name:{{ $user->name }}</strong></h4>
                        <div class="profile-info">
                            <p ><strong>Email: </strong>{{ $user->email }}</p>
                            <p><strong>City:</strong> {{ $user->location }}</p>
                            <p><strong>Phone:</strong> {{ $user->mobile }}</p>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</body>
</html>
