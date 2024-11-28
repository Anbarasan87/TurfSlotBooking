<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Turfs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Your Turfs</h2>
        <a href="{{ route('turfs.create') }}" class="btn btn-primary">Add New Turf</a>

        <h3>Your Turfs</h3>
        <table class="table">
            <thead>
                <tr>
                <th>Name</th>
                        <th>Location</th>
                        <th>Sport Type</th>
                        <th>Price per Hour</th>
                        <th>Availability</th>
                        <th>Image</th>
                        <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($turfs as $turf)
                    <tr>
                        <td>{{ $turf->name }}</td>
                        <td>{{ $turf->location }}</td>
                        <td>{{ $turf->sport_type }}</td>
                        <td>${{ $turf->price_per_hour }}</td>
                        <td>{{ implode(', ', json_decode($turf->availability, true)) }}</td>
                        <td>
                            @if($turf->image)
                                <img src="{{ asset('storage/'.$turf->image) }}" alt="{{ $turf->name }}" width="100">
                            @else
                                No Image
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('turfs.edit', $turf->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('turfs.destroy', $turf->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
