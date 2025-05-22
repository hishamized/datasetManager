@extends('layouts.app')

@section('externalCSS')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endsection

@section('content')

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <div class="logo">
        <img src="{{ asset('shield.png') }}" alt="Logo" class="img-fluid">
    </div>
    <div class="app-name">IDS Datasets</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{route('dashboard')}}" class="nav-link text-white">Dashboard</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('project.create') }}" class="nav-link text-white">Create New Project</a>
        </li>
        <li class="nav-item">
            <a href="{{route('projects.view')}}" class="nav-link text-white">View Your Projects</a>
        </li>
        @if(auth()->user()->role() == 'master')
        <li class="nav-item">
            <a href="{{ route('showSignUpPage') }}" class="nav-link text-white">Add a New Admin</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('showChatsPage', ['user_id' => auth()->user()->id ] ) }}" class="nav-link text-white">Chats</a>
        </li>
        @endif
    </ul>
</div>

<!-- Overlay -->
<div id="menu-overlay" class="menu-overlay"></div>


<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex flex-row justify-content-between align-items-center">
                    <div>Dashboard</div>
                    <div id="menu-btn" class="text-dark">
                        <span class="hamburger">&#9776;</span>
                        <span class="cross">&times;</span>
                    </div>
                </div>

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

                    <h5>Welcome to your dashboard! We have the following features for you.</h5>

                    <div class="mt-4">
                        <ul class="list-group">
                            <li class="list-group-item">Overview of all projects and activities</li>
                            <li class="list-group-item">Create new projects</li>
                            <li class="list-group-item">View and manage existing projects</li>
                            <li class="list-group-item">For Admins: Manage users and assign roles</li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const menuBtn = document.getElementById('menu-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('menu-overlay');

    menuBtn.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');

        if (sidebar.classList.contains('active')) {
            document.querySelector('.hamburger').style.display = 'none';
            document.querySelector('.cross').style.display = 'inline';
        } else {
            document.querySelector('.hamburger').style.display = 'inline';
            document.querySelector('.cross').style.display = 'none';
        }
    });

    overlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.querySelector('.hamburger').style.display = 'inline';
        document.querySelector('.cross').style.display = 'none';
    });
</script>
@endsection
