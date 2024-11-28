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
        function searchBooking() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const filterBy = document.getElementById('filterBy').value;
            const rows = document.querySelectorAll('#bookingTable tbody tr');

            rows.forEach(row => {
                const userName = row.children[1].textContent.toLowerCase();
                const turfName = row.children[2].textContent.toLowerCase();
                const bookingDate = row.children[3].textContent.toLowerCase();
                const timeSlot = row.children[4].textContent.toLowerCase();

                let matches = false;

                switch (filterBy) {
                    case 'user_name':
                        matches = userName.includes(input);
                        break;
                    case 'turf_name':
                        matches = turfName.includes(input);
                        break;
                    case 'booking_date':
                        matches = bookingDate.includes(input);
                        break;
                    case 'time_slot':
                        matches = timeSlot.includes(input);
                        break;
                    default:
                        matches = userName.includes(input) || turfName.includes(input) || bookingDate.includes(input) || timeSlot.includes(input);
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
                <a class="mdl-navigation__link" href="{{ route('dashboard.owner') }}">Dashboard</a>
                <a class="mdl-navigation__link" href="{{ route('owner.turfs') }}">My Turfs</a>
                <a class="mdl-navigation__link" href="{{ route('owner.bookings') }}">Manage Bookings</a>
            </nav>
        </div>
        <main class="mdl-layout__content mdl-color--grey-100">
            <div class="container my-4">
            <a href="{{ route('dashboard.user') }}" class="btn btn-primary mb-3">Add Booking</a>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <select id="filterBy" class="form-control" onchange="searchBooking()">
                            <option value="all">All</option>
                            <option value="user_name">User Name</option>
                            <option value="turf_name">Turf Name</option>
                            <option value="booking_date">Booking Date</option>
                            <option value="time_slot">Time Slot</option>
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $index => $booking)
                            <tr>
                                <td>{{ $loop->iteration }}</td> 
                                <td>{{ $booking->user->name }}</td> 
                                <td>{{ $booking->turf->name }}</td>
                                <td>{{ $booking->booking_date }}</td>
                                <td>{{ $booking->time_slot }}</td>
                                <td>₹{{ $booking->total_price }}</td>
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
                                <td colspan="7">
                                    <form action="{{ route('owner.bookings.update', ['id' => $booking->id, 'type' => 'bookings']) }}" method="POST">
                                        @csrf
                                        @method('get')
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
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-success btn-sm">Update</button>
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
    <script>
    function toggleEdit(bookingId) {
        
        const editForm = document.getElementById('edit-form-' + bookingId);
        
         if (editForm.style.display === "none" || editForm.style.display === "") {
            editForm.style.display = "table-row";
        } else {
            editForm.style.display = "none"; 
        }
    }
</script>

    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</body>
</html>