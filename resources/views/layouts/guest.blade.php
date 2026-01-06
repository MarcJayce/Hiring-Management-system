<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light text-dark fixed-top">
        <!-- <a class="navbar-brand text-dark" href="/">
            <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="sidebar-logo img-fluid">
        </a> -->
        <a class="navbar-brand text-dark" href="/">
            <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="sidebar-logo img-fluid" style="width: 100px; height: auto;">
        </a>


        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <!-- <li class="nav-item active">
                    <a class="nav-link text-dark" href="/">Home <span class="sr-only"></span></a>
                </li> -->
                <li class="nav-item active">
                    <a class="nav-link text-dark" href="{{ route('jobs.list') }}">Find Open Jobs <span class="sr-only"></span></a>
                </li>
            </ul>
            <!-- <a href="{{ route('login') }}" class="btn btn-purple my-2 my-sm-0">Login</a> -->
        </div>
    </nav>
    <div class="guest-content mt-5">
        @yield('content')
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>