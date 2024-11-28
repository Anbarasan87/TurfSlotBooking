<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Turfs</title>
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

        .actions-buttons {
            display: flex;
            gap: 5px;
        }

        table thead th,
        table tbody td {
            vertical-align: middle;
            text-align: center;
        }

        .img-thumbnail {
            max-width: 50px;
            max-height: 50px;
        }

        .edit-form input,
        .edit-form select {
            margin-bottom: 10px;
        }

        .form-container {
            margin: 20px;
        }
    </style>
    <script>
        function toggleEdit(id) {
            const editForm = document.getElementById('edit-form-' + id);
            const displayRow = document.getElementById('display-row-' + id);

            // Toggle the display of the edit form and display row
            if (editForm.style.display === 'none') {
                editForm.style.display = 'table-row';
                displayRow.style.display = 'none';
            } else {
                editForm.style.display = 'none';
                displayRow.style.display = 'table-row';
            }
        }

        function searchTurf() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#turfTable tbody tr[id^="display-row-"]');
            rows.forEach(row => {
                const turfName = row.children[2].textContent.toLowerCase();
                const ownerName = row.children[1].textContent.toLowerCase();
                row.style.display = (turfName.includes(input) || ownerName.includes(input)) ? '' : 'none';
            });
        }

        function filterSportType() {
            const selectedType = document.getElementById('sportFilter').value;
            const rows = document.querySelectorAll('#turfTable tbody tr[id^="display-row-"]');
            rows.forEach(row => {
                const sportType = row.children[5].textContent;
                row.style.display = (selectedType === 'all' || sportType === selectedType) ? '' : 'none';
            });
        }
    </script>
</head>

<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Manage Turfs</span>
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
                <a href="{{ route('admin.create', 'turfs') }}" class="btn btn-primary mb-3">Add Turf</a>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search by Turf Name or Owner Name" onkeyup="searchTurf()">
                    </div>
                    <div class="col-md-6">
                        <select id="sportFilter" class="form-control" onchange="filterSportType()">
                            <option value="all">All Sports</option>
                            <option value="Football">Football</option>
                            <option value="Cricket">Cricket</option>
                            <option value="Basketball">Basketball</option>
                            <option value="Tennis">Tennis</option>
                        </select>
                    </div>
                </div>

                <table class="table table-striped table-bordered" id="turfTable">
                    <thead class="table-light">
                        <tr>
                            <th>S.No</th>
                            <th>Owner Name</th>
                            <th>Turf Name</th>
                            <th>Location</th>
                            <th>Image</th>
                            <th>Sport Type</th>
                            <th>Price Per Hour</th>
                            <th>Availability</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
                        @foreach($data as $turf)
                        <tr id="display-row-{{ $turf->id }}">
                            <td>{{ $counter++ }}</td>
                            <td>{{ $turf->owner_name }}</td>
                            <td>{{ $turf->name }}</td>
                            <td>{{ $turf->location }}</td>
                            <td><img src="{{ asset('storage/images/' . ($turf->image ?? 'new_turf.jpeg')) }}" alt="Turf Image" class="img-thumbnail"></td>
                            <td>{{ $turf->sport_type }}</td>
                            <td>₹{{ $turf->price_per_hour }}</td>
                            <td>{{ $turf->availability }}</td>
                            <td>
                                <div class="actions-buttons">
                                    <button onclick="toggleEdit({{ $turf->id }})" class="btn btn-warning btn-sm">Edit</button>
                                    <form action="{{ route('admin.destroy', ['type' => 'turfs', 'id' => $turf->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr id="edit-form-{{ $turf->id }}" style="display:none;">
                            <td colspan="9">
                                <form action="{{ route('admin.update', ['id' => $turf->id, 'type' => 'turfs']) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="name">Turf Name</label>
                                            <input type="text" name="name" value="{{ $turf->name }}" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="location">Location</label>
                                            <input type="text" name="location" value="{{ $turf->location }}" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="sport_type">Sport Type</label>
                                            <select name="sport_type" class="form-control" required>
                                                <option value="Football" {{ $turf->sport_type === 'Football' ? 'selected' : '' }}>Football</option>
                                                <option value="Cricket" {{ $turf->sport_type === 'Cricket' ? 'selected' : '' }}>Cricket</option>
                                                <option value="Basketball" {{ $turf->sport_type === 'Basketball' ? 'selected' : '' }}>Basketball</option>
                                                <option value="Tennis" {{ $turf->sport_type === 'Tennis' ? 'selected' : '' }}>Tennis</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="availability">Availability</label>
                                            <select name="availability" class="form-control" required>
                                                <option value="Available" {{ $turf->availability === 'Available' ? 'selected' : '' }}>Available</option>
                                                <option value="Unavailable" {{ $turf->availability === 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="price_per_hour">Price Per Hour</label>
                                            <input type="number" name="price_per_hour" value="{{ $turf->price_per_hour }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success mt-3">Update</button>
                                    <button type="button" class="btn btn-secondary mt-3" onclick="toggleEdit({{ $turf->id }})">Cancel</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>
