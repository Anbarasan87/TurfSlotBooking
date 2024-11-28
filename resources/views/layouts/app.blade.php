<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @stack('styles') 

    <style>
        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            visibility: hidden; 
        }

        #loader .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #007bff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .container {
            visibility: hidden;
        }
    </style>
</head>
<body>
    <div id="loader">
        <div class="spinner"></div>
    </div>

    <div class="container">
        @yield('content') 
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts') 

    <script>
        window.addEventListener('load', function() {
            const loader = document.getElementById('loader');
            const content = document.querySelector('.container');

            loader.style.visibility = 'hidden';
            content.style.visibility = 'visible';
        });
    </script>
</body>
</html>
