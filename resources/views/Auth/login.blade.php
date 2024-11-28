<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content" style="background-color: #0e1a35; border-radius: 12px; overflow: hidden;">
            <div class="modal-body p-4">
                <h4 class="text-center text-white mb-4">Login</h4>
                <form method="POST" action="{{ route('login') }}" style="width: 100%;">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label text-white">Email Address</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-white">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-white" for="remember">Remember Me</label>
                    </div>
                    <div class="text-center mt-3">
                        <a href="#" class="text-white" id="showRegisterModal">Do you have No account? Register</a>
                    </div>
                    <button type="submit" class="btn btn-light w-100">Login</button>
                    @if (Route::has('password.request'))
                        <div class="text-center mt-3">
                            <a href="{{ route('password.request') }}" class="text-white" style="font-size: 0.9rem;">Forgot Your Password?</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {      
        $('#showRegisterModal').on('click', function() {
            $('#registerModal').modal('show');  
            $('#loginModal').modal('hide');       
            $('#registerModalContent').load('{{ route('register') }}');
        });
        $('#showLoginModal').on('click', function() {
            $('#registerModal').modal('hide');  
            $('#loginModal').modal('show');     
            $('#loginModalContent').load('{{ route('login') }}');
        });
    });
</script>