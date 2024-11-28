<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings</title>
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
        .status-pending {
            color: #856404;
            background-color: #fff3cd;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }
        .status-confirmed {
            color: #155724;
            background-color: #d4edda;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }
        .status-cancelled {
            color: #721c24;
            background-color: #f8d7da;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Your Bookings</span>
                <div class="mdl-layout-spacer"></div>
                <form action="{{ route('logout') }}" method="POST" style="margin-left: 20px;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <header class="mdl-layout__drawer-header" style="padding: 16px; background-color: #1c313a; text-align: center;">
                <span style="color: #ffffff; font-weight: bold; font-size: 1.2rem;">
                    {{ auth()->user()->name ?? 'Guest' }}
                </span>
            </header>
            <nav class="mdl-navigation">
                <a href="{{ route('dashboard.user', ['view' => 'profile']) }}" class="mdl-navigation__link">My Profile</a>
                <a href="{{ route('dashboard.user', ['view' => 'bookings']) }}" class="mdl-navigation__link">My Bookings</a>
                <div class="text-center">
                    <a href="{{ route('dashboard.user') }}" class="btn btn-muted">Back to Dashboard</a>
                </div>
            </nav>
        </div>
        <main class="mdl-layout__content mdl-color--grey-100">
            <div class="mdl-grid demo-content">
                <div class="mdl-cell mdl-cell--12-col">
                    <div class="profile-card">
                        <div class="profile-info">
                            <h4>Your Bookings</h4>
                            @if($bookings->isEmpty())
                                <p>You have no bookings.</p>
                            @else
                                <table class="table table-striped table-bordered" id="bookingsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Turf</th>
                                            <th>Date</th>
                                            <th>Time Slot</th>
                                            <th>Status</th>
                                            <th>Total Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $booking)
                                            @php
                                                $timeParts = explode('-', $booking->time_slot);
                                                $startTime = $timeParts[0] ?? null;
                                                $endTime = $timeParts[1] ?? null;
                                            @endphp
                                            <tr id="booking-{{ $booking->id }}">
                                                <td>{{ $booking->turf->name }}</td>
                                                <td>{{ $booking->booking_date }}</td>
                                                <td>
                                                    {{ $startTime }} - {{ $endTime }}
                                                </td>
                                                <td class="status">
                                                    @if($booking->status === 'Pending')
                                                        <span class="status-pending">Pending</span>
                                                    @elseif($booking->status === 'Confirmed')
                                                        <span class="status-confirmed">Confirmed</span>
                                                    @elseif($booking->status === 'Cancelled')
                                                        <span class="status-cancelled">Cancelled</span>
                                                    @endif
                                                </td>
                                                <td>₹{{ number_format($booking->total_price, 2) }}</td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm cancel-btn" data-id="{{ $booking->id }}" data-date="{{ $booking->booking_date }}" data-time-end="{{ $endTime }}">Cancel Booking</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.cancel-btn').click(function () {
                const bookingId = $(this).data('id');
                const bookingDate = $(this).data('date');
                const endTime = $(this).data('time-end');
                const currentDate = new Date().toISOString().split('T')[0];
                const currentTime = new Date().toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });

                if (bookingDate < currentDate || (bookingDate === currentDate && endTime <= currentTime)) {
                    alert('You cannot cancel past bookings.');
                    return;
                }

                
                if (confirm('Are you sure you want to cancel this booking?')) {
                    
                    $.ajax({
                        url: `/booking/${bookingId}/cancel`, 
                        type: 'PATCH',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            $(`#booking-${bookingId} .status`).html('<span class="status-cancelled">Cancelled</span>');
                            alert('Booking cancelled successfully.');
                        },
                        error: function () {
                            alert('Error cancelling the booking.');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
