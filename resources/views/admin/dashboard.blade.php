<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .mdl-navigation__link {
            color: #ffffff;
        }
        .mdl-layout__drawer {
            background-color: #1c313a;
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
                <span class="mdl-layout-title">Admin Dashboard</span>
                <div class="mdl-layout-spacer"></div>
                <form action="{{ route('logout') }}" method="POST" style="margin-left: 20px;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
                    <label class="mdl-button mdl-js-button mdl-button--icon" for="search">
                        <i class="material-icons"></i>
                    </label>
                    <div class="mdl-textfield__expandable-holder">
                        <input class="mdl-textfield__input" type="text" id="search">
                        <label class="mdl-textfield__label" for="search"></label>
                    </div>
                </div>
            </div>
        </header>
        <div class="mdl-layout__drawer">
        <header class="mdl-layout__drawer-header" style="padding: 16px; background-color: #1c313a; text-align: center;">
                <span style="color: #ffffff; font-weight: bold; font-size: 1.2rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6);">
                    {{ auth()->user()->name ?? 'Guest' }}
                </span>
            </header>
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="mdl-navigation__link" href="{{ route('admin.manage', ['type' => 'users']) }}">Manage Users</a>
                <a class="mdl-navigation__link" href="{{ route('admin.manage', ['type' => 'turfs']) }}">Manage Turfs</a>
                <a class="mdl-navigation__link" href="{{ route('admin.manage', ['type' => 'bookings']) }}">Manage Bookings</a>
                <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" id="downloadBookingsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"style="background-color: transparent; color: white; font-weight: bold; border: none; box-shadow: none; padding: 0; cursor: pointer;margin-top:15px; margin-left: 40px;">
    Download Details
</button>

<div class="dropdown-menu" aria-labelledby="downloadBookingsDropdown">
    <a class="dropdown-item" href="{{ route('dashboard.admin', ['download' => 1, 'type' => 'all']) }}">All (Users, Turfs, and Bookings)</a>
    <a class="dropdown-item" href="{{ route('dashboard.admin', ['download' => 1, 'type' => 'user']) }}">Users Only</a>
    <a class="dropdown-item" href="{{ route('dashboard.admin', ['download' => 1, 'type' => 'turf']) }}">Turfs Only</a>
    <a class="dropdown-item" href="{{ route('dashboard.admin', ['download' => 1, 'type' => 'booking']) }}">Bookings Only</a>
</div>

            </nav>
        </div>
        <main class="mdl-layout__content mdl-color--grey-100">
    <div class="mdl-grid demo-content">
        <div class="mdl-cell mdl-cell--12-col" style="text-align: center; padding: 20px;">
            <h2 style="font-size: 2em; color: #333;">Welcome to the Admin Dashboard</h2>
            <p style="font-weight: bold; color: rgba(0, 0, 0, 0.6);">Here you can manage users, turfs, and bookings.</p>
        </div>

        <div class="mdl-cell mdl-cell--12-col mdl-grid" style="gap: 20px; justify-content: center;">
           
<div class="card" style="width: 100%; max-width: 300px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); border-radius: 8px; overflow: hidden; background: #fff;">
    <a href="{{ route('admin.manage', ['type' => 'users']) }}" style="text-decoration: none;">
        <div style="background-color: #607d8b; color: white; padding: 20px;">
            <h2 style="font-size: 1.5em; margin: 0;">Total Users</h2>
        </div>
    </a>
    <div class="card-body" style="padding: 20px; text-align: center;">
        <span style="font-size: 2em; font-weight: bold; color: #333;">{{ $userCount ?? 0 }}</span>
    </div>
</div>


            <div class="card" style="width: 100%; max-width: 300px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); border-radius: 8px; overflow: hidden; background: #fff;">
                <a href="{{ route('admin.manage', ['type' => 'turfs']) }}" style="text-decoration: none;">
                    <div style="background-color: #28a745; color: white; padding: 20px;">
                        <h2 style="font-size: 1.5em; margin: 0;">Total Turfs</h2>
                    </div>
                </a>
                <div class="card-body" style="padding: 20px; text-align: center;">
                    <span style="font-size: 2em; font-weight: bold; color: #333;">{{ $turfCount ?? 0 }}</span>
                </div>
            </div>

            <div class="card" style="width: 100%; max-width: 300px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); border-radius: 8px; overflow: hidden; background: #fff;">
                <a href="{{ route('admin.bookings', ['type' => 'bookings']) }}" style="text-decoration: none;">
                    <div style="background-color: #ffa500; color: white; padding: 20px;">
                        <h2 style="font-size: 1.5em; margin: 0;">Total Bookings</h2>
                    </div>
                </a>
                <div class="card-body" style="padding: 20px; text-align: center;">
                    <span style="font-size: 2em; font-weight: bold; color: #333;">{{ $bookingCount ?? 0 }}</span>
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
