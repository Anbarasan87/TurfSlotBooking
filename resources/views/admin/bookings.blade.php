<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
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
        }
    </style>
    <script>
        function toggleEdit(id) {
            const editForm = document.getElementById('edit-form-' + id);
            const displayRow = document.getElementById('display-row-' + id);

            if (editForm.style.display === 'none') {
                editForm.style.display = 'table-row';
                displayRow.style.display = 'none';
            } else {
                editForm.style.display = 'none';
                displayRow.style.display = 'table-row';
            }
        }

        function searchBooking() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const filterBy = document.getElementById('filterBy').value;
            const rows = document.querySelectorAll('#bookingTable tbody tr[id^="display-row-"]');

            rows.forEach(row => {
                const userId = row.children[1].textContent.toLowerCase();
                const turfName = row.children[2].textContent.toLowerCase();
                const bookingDate = row.children[3].textContent.toLowerCase();
                const timeSlot = row.children[4].textContent.toLowerCase();

                let matches = false;

                switch (filterBy) {
                    case 'user_id':
                        matches = userId.includes(input);
                        break;
                    case 'turf_id':
                        matches = turfName.includes(input);
                        break;
                    case 'booking_date':
                        matches = bookingDate.includes(input);
                        break;
                    case 'time_slot':
                        matches = timeSlot.includes(input);
                        break;
                    default:
                        matches = userId.includes(input) || turfName.includes(input) || bookingDate.includes(input) || timeSlot.includes(input);
                }

                row.style.display = matches ? '' : 'none';
            });
        }
    </script>
</head>
<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Manage Bookings</span>
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
                <a class="mdl-navigation__link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="mdl-navigation__link" href="{{ route('admin.manage', ['type' => 'users']) }}">Manage Users</a>
                <a class="mdl-navigation__link" href="{{ route('admin.manage', ['type' => 'turfs']) }}">Manage Turfs</a>
                <a class="mdl-navigation__link" href="{{ route('admin.manage', ['type' => 'bookings']) }}">Manage Bookings</a>
            </nav>
        </div>
        <main class="mdl-layout__content mdl-color--grey-100">
            <div class="container my-4">
            <a href="{{ route('dashboard.user') }}" class="btn btn-primary mb-3">Add Booking</a>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <select id="filterBy" class="form-control" onchange="searchBooking()">
                            <option value="all">All</option>
                            <option value="user_id">User ID</option>
                            <option value="turf_id">Turf Name</option>
                            <option value="booking_date">Booking Date</option>
                            <option value="status">Booking Status</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search..." onkeyup="searchBooking()">
                    </div>
                </div>

                <table class="table table-striped table-bordered" id="bookingTable">
                    <thead class="table-light">
                        <tr>
                            <th>S.No</th>
                            <th>User Name</th>
                            <th>Turf Name</th>
                            <th>Booking Date</th>
                            <th>Time Slot</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $index => $booking)
                            <tr id="display-row-{{ $booking->id }}">
                                <td>{{ $index + 1 }}</td> 
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $booking->turf->name }}</td>
                                <td>{{ $booking->booking_date }}</td>
                                <td>{{ $booking->time_slot }}</td>
                                <td>₹{{ $booking->total_price }}</td>
                                <td>{{ ucfirst($booking->status) }}</td>
                                <td>
                                    <button onclick="toggleEdit({{ $booking->id }})" class="btn btn-warning btn-sm">Edit</button>
                                    <form action="{{ route('admin.destroy', ['type' => 'bookings', 'id' => $booking->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <tr id="edit-form-{{ $booking->id }}" style="display:none;">
                                <td colspan="8">
                                    <form action="{{ route('admin.update', ['id' => $booking->id, 'type' => 'bookings']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col">
                                                <select name="user_id" class="form-control" required>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}" {{ $user->id == $booking->user_id ? 'selected' : '' }}>{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col">
                                                <select name="turf_id" class="form-control" required>
                                                    @foreach($turfs as $turf)
                                                        <option value="{{ $turf->id }}" {{ $turf->id == $booking->turf_id ? 'selected' : '' }}>{{ $turf->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col">
                                                <input type="date" class="form-control" name="booking_date" value="{{ $booking->booking_date }}" required>
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control" name="time_slot" value="{{ $booking->time_slot }}" required>
                                            </div>
                                            <div class="col">
                                                <input type="number" class="form-control" name="total_price" value="{{ $booking->total_price }}" required>
                                            </div>
                                            <div class="col">
                                                <select name="status" class="form-control" required>
                                                    <option value="Pending" {{ $booking->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="Confirmed" {{ $booking->status == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                    <option value="Cancelled" {{ $booking->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <button type="submit" class="btn btn-success btn-sm">Update</button>
                                                <button type="button" class="btn btn-secondary btn-sm" onclick="toggleEdit({{ $booking->id }})">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
