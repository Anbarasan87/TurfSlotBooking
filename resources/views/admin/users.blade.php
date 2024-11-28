<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Users</title>
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
        .counter-card {
            background-color: #4caf50;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
        }
        .counter-card-owner {
            background-color: #ff9800;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
        }
    </style>
    <script>
        function toggleEdit(id) {
            const displayRow = document.getElementById('display-row-' + id);
            const editForm = document.getElementById('edit-form-' + id);

            if (editForm.style.display === 'none') {
                editForm.style.display = 'table-row';
                displayRow.style.display = 'none';
            } else {
                editForm.style.display = 'none';
                displayRow.style.display = 'table-row';
            }
        }

        function searchUser() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#userTable tbody tr[id^="display-row-"]');

            rows.forEach(row => {
                const userName = row.children[1].textContent.toLowerCase(); 
                const userEmail = row.children[2].textContent.toLowerCase();
                if (userName.includes(input) || userEmail.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function filterUserRole() {
            const selectedRole = document.getElementById('roleFilter').value;
            const rows = document.querySelectorAll('#userTable tbody tr[id^="display-row-"]');

            rows.forEach(row => {
                const userRole = row.children[3].textContent.toLowerCase(); 
                if (selectedRole === '' || userRole === selectedRole) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</head>
<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Manage Users</span>
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
            <div class="container mt-5">
                @if(isset($message))
                    <div class="alert alert-warning">{{ $message }}</div>
                @else
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="counter-card">
                                <h5>User Count</h5>
                                <p>{{ $userCount }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="counter-card-owner">
                                <h5>Owner Count</h5>
                                <p>{{ $ownerCount }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search users by Name or Email" onkeyup="searchUser()">
                        </div>
                        <div class="col-md-6">
                            <select id="roleFilter" class="form-control" onchange="filterUserRole()">
                                <option value="">All Roles</option>
                                <option value="user">User</option>
                                <option value="owner">Owner</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped" id="userTable">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Location</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($data as $user)
                                <tr id="display-row-{{ $user->id }}">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td>{{ $user->location ?? 'N/A' }}</td>
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <button onclick="toggleEdit({{ $user->id }})" class="btn btn-warning btn-sm">Edit</button>
                                        <form action="{{ route('admin.destroy', ['id' => $user->id, 'type' => 'users']) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <tr id="edit-form-{{ $user->id }}" style="display:none;">
                                    <td colspan="7">
                                        <form action="{{ route('admin.update', ['id' => $user->id, 'type' => 'users']) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col">
                                                    <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                                                </div>
                                                <div class="col">
                                                    <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                                                </div>
                                                <div class="col">
                                                    <input type="text" class="form-control" name="role" value="{{ $user->role }}" required>
                                                </div>
                                                <div class="col">
                                                    <input type="text" class="form-control" name="location" value="{{ $user->location }}" required>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-success btn-sm">Update</button>
                                                    <button type="button" onclick="toggleEdit({{ $user->id }})" class="btn btn-secondary btn-sm">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </main>
    </div>

    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</body>
</html>
