<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Turfs</title>
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
        function searchTurf() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#turfTable tbody tr[id^="display-row-"]');

            rows.forEach(row => {
                const turfName = row.children[2].textContent.toLowerCase(); // Turf Name
                const ownerName = row.children[1].textContent.toLowerCase(); // Owner Name
                if (turfName.includes(input) || ownerName.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

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
    </script>
</head>
<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">My Turfs</span>
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
                <a class="mdl-navigation__link" href="{{ route('owner.turfs') }}">Turfs</a>
                <a class="mdl-navigation__link" href="{{ route('owner.bookings') }}">Bookings</a>
            </nav>
        </div>
        <main class="mdl-layout__content mdl-color--grey-100">
            <div class="container my-4">
                <a href="{{ route('admin.create', 'turfs') }}" class="btn btn-primary mb-3">Add Turf</a>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search by Turf Name or Owner Name" onkeyup="searchTurf()">
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
                        @foreach($turfs as $turf)
                            <tr id="display-row-{{ $turf->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $turf->owner_name }}</td>
                                <td>{{ $turf->name }}</td>
                                <td>{{ $turf->location }}</td>
                                <td>
                                    <img src="{{ asset('storage/images/' . ($turf->image ?? 'default.jpg')) }}" alt="Turf Image" class="img-fluid" style="max-width: 50px; max-height: 50px;">
                                </td>
                                <td>{{ $turf->sport_type }}</td>
                                <td>₹{{ $turf->price_per_hour }}</td>
                                <td>{{ $turf->availability }}</td>
                                <td>
                                    <button onclick="toggleEdit({{ $turf->id }})" class="btn btn-warning btn-sm">Edit</button>
                                    <form action="{{ route('admin.destroy', ['type' => 'turfs', 'id' => $turf->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <tr id="edit-form-{{ $turf->id }}" style="display:none;">
                                <td colspan="9">
                                    <form action="{{ route('owner.turfs.update', ['id' => $turf->id, 'type' => 'turfs']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" class="form-control" name="owner_name" value="{{ $turf->owner_name }}" required placeholder="Owner Name">
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control" name="name" value="{{ $turf->name }}" required placeholder="Turf Name">
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control" name="location" value="{{ $turf->location }}" required placeholder="Location">
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control" name="sport_type" value="{{ $turf->sport_type }}" required placeholder="Sport Type">
                                            </div>
                                            <div class="col">
                                                <input type="number" class="form-control" name="price_per_hour" value="{{ $turf->price_per_hour }}" required placeholder="Price Per Hour">
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control" name="availability" value="{{ $turf->availability }}" required placeholder="Availability">
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-success btn-sm">Update</button>
                                                <button type="button" onclick="toggleEdit({{ $turf->id }})" class="btn btn-secondary btn-sm">Cancel</button>
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

    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</body>
</html>
