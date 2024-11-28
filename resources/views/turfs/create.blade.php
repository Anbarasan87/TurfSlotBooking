<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Turf</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            background-color: #0e1a35;
            color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            margin: 50px auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }
        .form-control {
            border: 1px solid #ffffff;
            background-color: #1c2a47;
            color: #ffffff;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-control:focus {
            border: 2px solid #ffffff;
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }
        .btn-primary {
            background-color: #ffffff;
            color: #0e1a35;
            border: none;
            font-weight: bold;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Add New Turf</h2>
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('turfs.create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Turf Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" id="location" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="sport_type" class="form-label">Sport Type</label>
                <input type="text" name="sport_type" id="sport_type" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="price_per_hour" class="form-label">Price per Hour</label>
                <input type="number" name="price_per_hour" id="price_per_hour" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="availability" class="form-label">Select Availability:</label>
                <select name="availability[]" id="availability" class="form-control" multiple>
                    <option value="Available">Available</option>
                    <option value="Unavailable">Unavailable</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-secondary">Add Turf</button>
        </form>
    </div>
</body>
</html>
