<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header text-center">
                <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="sidebar-logo img-fluid">
            </div>

            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="/dashboard">Overview</a></li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="collapse" href="#jobsCollapse">Jobs</a>
                    <div class="collapse" id="jobsCollapse">
                        <ul class="nav flex-column">
                            <li class="nav-item"><a class="nav-link" href="{{ route('vacancies.interns') }}">Intern</a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('vacancies.employees') }}">Employee</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="collapse"
                        href="#applicationsCollapse">Applications</a>
                    <div class="collapse" id="applicationsCollapse">
                        <ul class="nav flex-column">
                            <li class="nav-item"><a class="nav-link" href="{{ route('intern.create') }}">Intern Form</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('employee.create') }}">Employee
                                    Form</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('candidates.index') }}">Candidates</a></li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="collapse" href="#interviewsCollapse">Interviews</a>
                    <div class="collapse" id="interviewsCollapse">
                        <ul class="nav flex-column">
                            <li class="nav-item"><a class="nav-link" href="{{ route('schedule.create') }} ">Schedule
                                    Interview</a></li>
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('interviews.scheduled') }}">Conduct Interview</a></li>
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('interviews.completed') }}">Interview Records</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('interviews.index') }}">Interview
                                    Questions</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('calendar.index') }}">Calendar</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('feedback.index') }}">Feedbacks</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                </li>

            </ul>
        </nav>

        <!-- Main Content -->
        <div class="content">
            <!-- Header -->
            <header class="header d-flex justify-content-between align-items-center">
                <div class="search-bar position-relative">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                    <div id="searchResults" class="list-group"></div>
                </div>

                <div class="header-icons d-flex align-items-center">
                    <div class="dropdown ml-3">
                        <a class="dropdown-toggle" href="#" role="button" id="userDropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name ?? 'User' }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="main-content">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- Load the search.js file -->
    <script src="{{ asset('js/search.js') }}"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>

    
    
</body>

</html>
