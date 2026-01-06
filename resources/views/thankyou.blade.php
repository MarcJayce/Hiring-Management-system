@extends('layouts.guest')

@section('title', 'Thank You')

@section('content')
<div class="d-flex flex-column justify-content-center align-items-center min-vh-100 text-center">
    <h1 class="display-4 text-success">Thank You!</h1>
    <p class="lead">Your internship application has been successfully submitted.</p>
    <p>We will review your application and get back to you soon.</p>

    <a href="/" class="btn btn-purple btn-lg mt-3">Back to Home</a>
</div>
@endsection
