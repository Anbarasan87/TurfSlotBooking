<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content" style="background-color: #0e1a35; border-radius: 12px; overflow: hidden;">
            <div class="modal-body p-4">
                <h4 class="text-center text-white mb-4">Register</h4>
                <form action="{{ route('register') }}" method="POST" style="width: 100%;">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label text-white">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label text-white">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-white">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label text-white">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                        @error('password_confirmation')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label text-white">Register as</label>
                        <select id="role" class="form-control form-control-sm" name="role" required>
                            <option value="user">User</option>
                            <option value="owner">Turf Owner</option>
                        </select>
                        @error('role')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="location" class="form-label text-white">Location</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="location" name="location" placeholder="Enter location manually" required>
                            <button class="btn btn-light" type="button" id="getLocationBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-crosshair" viewBox="0 0 16 16">
                                    <path d="M8.5.5a.5.5 0 0 0-1 0v.518A7 7 0 0 0 1.018 7.5H.5a.5.5 0 0 0 0 1h.518A7 7 0 0 0 7.5 14.982v.518a.5.5 0 0 0 1 0v-.518A7 7 0 0 0 14.982 8.5h.518a.5.5 0 0 0 0-1h-.518A7 7 0 0 0 8.5 1.018zm-6.48 7A6 6 0 0 1 7.5 2.02v.48a.5.5 0 0 0 1 0v-.48a6 6 0 0 1 5.48 5.48h-.48a.5.5 0 0 0 0 1h.48a6 6 0 0 1-5.48 5.48v-.48a.5.5 0 0 0-1 0v.48A6 6 0 0 1 2.02 8.5h.48a.5.5 0 0 0 0-1zM8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                                </svg>
                            </button>
                        </div>
                        @error('location')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-light w-100">Register</button>
                </form>
                <div class="text-center mt-3">
                    <a href="#" class="text-white" id="showLoginModal">Already have an account? Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('getLocationBtn').addEventListener('click', function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    reverseGeocode(latitude, longitude);
                },
                function (error) {
                    alert('Unable to retrieve your location. Please enter it manually.');
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    });

    function reverseGeocode(latitude, longitude) {
        const geocodeUrl = `https://api.geoapify.com/v1/geocode/reverse?lat=${latitude}&lon=${longitude}&apiKey=56b83b2ab3514e1f97104ade2b9686df`;
        fetch(geocodeUrl)
            .then((response) => response.json())
            .then((data) => {
                if (data && data.features && data.features.length > 0) {
                    const location = data.features[0].properties.city || data.features[0].properties.formatted;
                    document.getElementById('location').value = location;
                } else {
                    alert("Unable to retrieve location details. Please enter it manually.");
                }
            })
            .catch(() => {
                alert('Error fetching location data.');
            });
    }
</script>
