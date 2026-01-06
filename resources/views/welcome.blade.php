@extends('layouts.guest')

@section('content')
    <div id="mainContainer"
        class="d-flex flex-column justify-content-center align-items-center min-vh-100 text-center transition-move">
        <h1 class="display-4">Welcome to Chimes Consulting</h1>
        <p class="lead">We connect talented individuals with great opportunities. Start your journey with us today.</p>

        <a href="{{ route('jobs.list') }}" class="btn btn-purple btn-lg mt-3">Apply Now</a>

    </div>

    @if (session('feedback_exists'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: '{{ session('feedback_exists') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endsection
