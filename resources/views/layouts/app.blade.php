<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="./img/fav.png" type="image/x-icon">
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <title>Universal Inventory Management</title>
    <style>
        /* Override template's aggressive text-transform capitalization for main content area */
        .main-content, 
        .main-content p, 
        .main-content a, 
        .main-content h1, 
        .main-content h2, 
        .main-content h3, 
        .main-content h4, 
        .main-content h5, 
        .main-content h6, 
        .main-content span, 
        .main-content label, 
        .main-content input, 
        .main-content select, 
        .main-content textarea, 
        .main-content table, 
        .main-content th, 
        .main-content td {
            text-transform: none !important;
        }

        /* Standard Tailwind classes missing from Cleopatra's purged style.css */
        .my-8 { margin-top: 2rem !important; margin-bottom: 2rem !important; }
        .mb-8 { margin-bottom: 2rem !important; }
        .mt-8 { margin-top: 2rem !important; }
        .pt-8 { padding-top: 2rem !important; }
        .pt-5 { padding-top: 1.25rem !important; }
        .pt-6 { padding-top: 1.5rem !important; }
        .pb-6 { padding-bottom: 1.5rem !important; }
        .p-2 { padding: 0.5rem !important; }
        .p-2.5 { padding: 0.625rem !important; }
        .p-4 { padding: 1rem !important; }
        .pl-8 { padding-left: 2rem !important; }
        
        /* Absolute Positioning */
        .left-3 { left: 0.75rem !important; }
        .top-3 { top: 0.75rem !important; }
        
        /* Flexbox Gaps */
        .gap-2 { gap: 0.5rem !important; }
        .gap-3 { gap: 0.75rem !important; }
        .gap-4 { gap: 1rem !important; }
        .gap-8 { gap: 2rem !important; }
        
        /* Width & Height Utilities */
        .w-5 { width: 1.25rem !important; height: 1.25rem !important; }
        .h-5 { height: 1.25rem !important; width: 1.25rem !important; }
        
        /* Color & Border Utilities */
        .bg-teal-50 { background-color: #f0fdfa !important; }
        .bg-indigo-50 { background-color: #f5f3ff !important; }
        .bg-green-50 { background-color: #f0fdf4 !important; }
        .text-green-700 { color: #15803d !important; }
        .border-green-500 { border-color: #22c55e !important; }
        .border-l-4 { border-left-width: 4px !important; }
        
        .border-teal-100 { border-color: #ccfbf1 !important; }
        .border-teal-150 { border-color: #b2f5ea !important; }
        .border-gray-250 { border-color: #e2e8f0 !important; }
        .border-gray-150 { border-color: #edf2f7 !important; }
        
        /* Shadows & Borders */
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05) !important; }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05) !important; }
        .rounded-lg { border-radius: 0.5rem !important; }
    </style>
</head>

<body class="bg-gray-100">


    <!-- start navbar -->
    @include('layouts.partials.navbar')
    <!-- end navbar -->


    <!-- strat wrapper -->
    <div class="min-h-screen flex flex-row flex-wrap">

        <!-- start sidebar -->
        @include('layouts.partials.sidebar')
        <!-- end sidbar -->

        <!-- strat content -->
        <div class="bg-gray-100 flex-1 p-6 md:mt-16 main-content">

            @yield('content')


           

            <!-- footer -->
            @include('layouts.partials.footer')

            <!-- end footer -->



        </div>
        <!-- end content -->

    </div>
    <!-- end wrapper -->

    <!-- script -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <!-- end script -->


</body>

</html>
