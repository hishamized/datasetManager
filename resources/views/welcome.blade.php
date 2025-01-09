@extends('layouts.app')

@section('title', 'Welcome Page')

@section('content')
    <div class="container my-5">
        <div class="row align-items-center">
            <!-- Information section on the left -->
            <div class="col-md-6 text-center text-md-left mb-4 mb-md-0">
                <h1 class="display-4 font-weight-bold">Welcome to Our Machine Learning Web App</h1>
                <p class="lead mt-4">
                    This application is designed to help users explore and analyze machine learning datasets with ease.
                    Our platform supports project management, dataset visualization, and detailed insights into various machine learning models.
                </p>
                <p>
                    Whether you are a beginner or an expert, our web app provides tools to enhance your understanding of machine learning concepts and data.
                    Start managing your datasets today and gain deeper insights into your projects.
                </p>
                <a href="{{ route('showProjectsPublicly') }}" class="btn btn-primary btn-lg mt-3">Explore Projects</a>
            </div>

            <!-- Image section on the right (random image generator) -->
            <div class="col-md-6">
                <img src="https://picsum.photos/600/400?random=programming" alt="Machine Learning" class="img-fluid rounded">
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .col-md-6 {
                text-align: center;
            }
        }
    </style>
@endsection
