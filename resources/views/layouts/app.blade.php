<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="./img/fav.png" type="image/x-icon">
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <title>Welcome To Cleopatra</title>
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
        <div class="bg-gray-100 flex-1 p-6 md:mt-16">

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
