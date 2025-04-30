<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('image/favicon.png') }}" type="image/png" >

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                background: linear-gradient(135deg, #4161df 0%, #172b7d 100%);
                background-size: cover;
                background-attachment: fixed;
                position: relative;
                overflow: hidden;
                font-family: 'Figtree', sans-serif;
            }
            
            body::before {
                content: '';
                position: absolute;
                width: 40vw;
                height: 40vw;
                background: rgba(65, 97, 223, 0.6);
                border-radius: 50%;
                top: -15vw;
                left: -15vw;
                z-index: 0;
            }
            
            body::after {
                content: '';
                position: absolute;
                width: 50vw;
                height: 50vw;
                background: rgba(65, 97, 223, 0.4);
                border-radius: 50%;
                bottom: -20vw;
                right: -20vw;
                z-index: 0;
            }
            
            .form-input {
                border: none !important;
                padding: 0.75rem 1rem 0.75rem 2.5rem !important;
            }
            
            button[type="submit"] {
                background-color: #3b82f6 !important;
                transition: background-color 0.3s ease !important;
            }
            
            button[type="submit"]:hover {
                background-color: #2563eb !important;
            }
            
            .google-btn {
                background-color: #1f2937 !important;
            }
            
            .google-btn:hover {
                background-color: #111827 !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10">
            <div class="w-full sm:max-w-md px-6 py-8 overflow-hidden">
                {{ $slot }}
            </div>
        </div>
        
        <script>
            // Make sure all text inputs have proper styling
            document.addEventListener('DOMContentLoaded', function() {
                const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
                inputs.forEach(input => {
                    input.classList.add('form-input');
                });
            });
        </script>
    </body>
</html>