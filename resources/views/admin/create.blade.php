<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create {{ ucfirst($type) }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/userstyle.css') }}">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-group input[type="file"] {
            border: none;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="date"],
        .form-group input[type="number"],
        .form-group select {
            background-color: #f9f9f9;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #5cb85c;
            box-shadow: 0 0 5px rgba(0, 204, 0, 0.5);
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .form-group input:invalid {
            border-color: #f00;
        }

        .form-group input:valid {
            border-color: #28a745;
        }

        .form-group .form-control {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create {{ ucfirst($type) }}</h2>

        <form action="{{ route('admin.store', ['type' => $type]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if ($type == 'users')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>
            @elseif ($type == 'turfs')
                <div class="form-group">
                    <label for="owner_name">Owner Name</label>
                    <input type="text" name="owner_name" id="owner_name" class="form-control" value="{{ old('owner_name') }}" required>
                </div>
                <div class="form-group">
                    <label for="owner_id">Owner ID</label>
                    <select name="owner_id" id="owner_id" class="form-control" required>
                        <option value="">Select Owner</option>
                        @foreach($owners as $owner)
                            <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Turf Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}" required>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" class="form-control">
                </div>
                <div class="form-group">
                    <label for="sport_type">Sport Type</label>
                    <input type="text" name="sport_type" id="sport_type" class="form-control" value="{{ old('sport_type') }}" required>
                </div>
                <div class="form-group">
                    <label for="price_per_hour">Price per Hour</label>
                    <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" class="form-control" value="{{ old('price_per_hour') }}" required>
                </div>
                <div class="form-group">
                    <label for="availability">Availability</label>
                    <select name="availability" id="availability" class="form-control" required>
                        <option value="Available" {{ old('availability') == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Unavailable" {{ old('availability') == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                </div>
            @elseif ($type == 'bookings')
                <div class="form-group">
                    <label for="user_id">User</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="turf_id">Turf</label>
                    <select name="turf_id" id="turf_id" class="form-control" required>
                        <option value="">Select Turf</option>
                        @foreach($turfs as $turf)
                            <option value="{{ $turf->id }}" {{ old('turf_id') == $turf->id ? 'selected' : '' }}>{{ $turf->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="booking_date">Booking Date</label>
                    <input type="date" name="booking_date" id="booking_date" class="form-control" value="{{ old('booking_date') }}" required>
                </div>
                <div class="form-group">
                    <label for="time_slot">Time Slot</label>
                    <input type="text" name="time_slot" id="time_slot" class="form-control" value="{{ old('time_slot') }}" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Confirmed" {{ old('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="total_price">Total Price</label>
                    <input type="number" name="total_price" id="total_price" class="form-control" value="{{ old('total_price') }}" required>
                </div>
            @endif

            <button type="submit">Create {{ ucfirst($type) }}</button>
        </form>
    </div>
</body>
</html>
