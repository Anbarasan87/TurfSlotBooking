<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
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
                <span class="mdl-layout-title">Owner Dashboard</span>
                <div class="mdl-layout-spacer"></div>
                <form action="{{ route('logout') }}" method="POST" style="margin-left: 20px;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
        </header>
        <div class="mdl-layout__drawer">
        <header class="mdl-layout__drawer-header" style="padding: 16px; background-color: #1c313a; text-align: center;">
                <span style="color: #ffffff; font-weight: bold; font-size: 1.2rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6);">
                    {{ auth()->user()->name ?? 'Guest' }}
                </span>
            </header>
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="{{ route('dashboard.owner') }}">Dashboard</a>
                <a class="mdl-navigation__link" href="{{ route('owner.turfs') }}">Turfs</a>
                <a class="mdl-navigation__link" href="{{ route('owner.bookings') }}">Bookings</a>
                <div class="mdl-card__actions">
                    <div class="dropdown">
                    <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" id="downloadBookingsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"style="background-color: transparent; color: white; font-weight: bold; border: none; box-shadow: none; padding: 0; cursor: pointer;margin-top:15px; margin-left: 33px;">
    Download Details
</button>
                        <div class="dropdown-menu" aria-labelledby="downloadTurfsDropdown">
                            <a class="dropdown-item" href="{{ route('owner.download', ['type' => 'all']) }}">All (Turfs and Bookings)</a>
                            <a class="dropdown-item" href="{{ route('owner.download', ['type' => 'turf']) }}">Turfs Only</a>
                            <a class="dropdown-item" href="{{ route('owner.download', ['type' => 'booking']) }}">Bookings Only</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <main class="mdl-layout__content mdl-color--grey-100">
            <div class="mdl-grid demo-content">
                <div class="mdl-cell mdl-cell--12-col">
                    <h2>Welcome to the Owner Dashboard</h2>
                    <p>Manage your turfs and view bookings here.</p>
                </div>
                <div class="mdl-cell mdl-cell--12-col" style="display: flex; justify-content: space-between; gap: 20px;">
    <a href="{{ route('owner.turfs', ['type' => 'turfs']) }}" 
       class="mdl-card mdl-shadow--2dp" 
       style="flex: 1; text-decoration: none; color: inherit; margin-right: 10px;">
        <div style="background-color: #607d8b; color: white; padding: 20px;">
            <h2 class="text-center" style="font-size: 1.5em; margin: 0;">My Turfs</h2>
        </div>
        <div class="mdl-card__supporting-text" style="padding: 20px; text-align: center;">
            <span style="font-size: 2em; font-weight: bold; color: #333;">{{ $turfCount ?? 0 }}</span>
        </div>
    </a>

    <a href="{{ route('owner.bookings', ['type' => 'bookings']) }}" 
       class="mdl-card mdl-shadow--2dp" 
       style="flex: 1; text-decoration: none; color: inherit; margin-left: 10px;">
        <div style="background-color: #ff9800; color: white; padding: 20px;;">
            <h2 class="text-center"style="font-size: 1.5em; margin: 0;">My Bookings</h2>
        </div>
        <div class="mdl-card__supporting-text" style="padding: 20px; text-align: center;">
            <span style="font-size: 2em; font-weight: bold; color: #333;">{{ $bookingCount ?? 0 }}</span>
        </div>
    </a>
</div>

            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</body>
</html>
