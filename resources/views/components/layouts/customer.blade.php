<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Display - {{ config('app.name', 'Empire POS') }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a; /* Slate 900 */
            color: #f8fafc;
            overflow: hidden;
        }
        .outfit-font {
            font-family: 'Outfit', sans-serif;
        }
        .glass-panel {
            background: rgba(30, 41, 59, 0.7); /* Slate 800 with opacity */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border-radius: 24px;
        }
        .gradient-text {
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Smooth animations for list items */
        .list-enter-active, .list-leave-active {
            transition: all 0.5s ease;
        }
        .list-enter-from {
            opacity: 0;
            transform: translateX(-30px);
        }
        .list-leave-to {
            opacity: 0;
            transform: translateX(30px);
        }
    </style>
    @livewireStyles
    @vite(['resources/js/app.js'])
</head>
<body class="vh-100 vw-100 m-0 p-0 d-flex justify-content-center align-items-center">
    
    <!-- Background Animated Gradients -->
    <div style="position: absolute; top: -10%; left: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(56,189,248,0.15) 0%, transparent 70%); border-radius: 50%; z-index: -1;"></div>
    <div style="position: absolute; bottom: -10%; right: -10%; width: 60vw; height: 60vw; background: radial-gradient(circle, rgba(129,140,248,0.15) 0%, transparent 70%); border-radius: 50%; z-index: -1;"></div>

    <div class="w-100 h-100 p-4 d-flex">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
