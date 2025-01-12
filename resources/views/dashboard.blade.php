@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if (session('failure'))
                    <div class="alert alert-danger">
                        {{ session('failure') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @auth
                    <h4>Hello {{ auth()->user()->fullName }}</h4>
                    @endauth

                    <h4>Welcome to your dashboard!</h4>

                    <div class="mt-4">
                        <a href="{{ route('project.create') }}" class="btn btn-primary">Create New Project</a>
                        <a href="{{route('projects.view')}}" class="btn btn-primary">View Your Projects</a>
                        @if(auth()->user()->role() == 'master')
                        <a href="{{ route('showSignUpPage') }}" class="btn btn-primary">Add a New Admin</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
