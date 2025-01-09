@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Create New Project</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <form action="{{ route('project.store') }}" method="POST">
        @csrf

        <!-- Project Title -->
        <div class="form-group">
            <label for="title">Project Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
        </div>

        <!-- Start Date -->
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
        </div>

        <!-- End Date -->
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" required>
        </div>

        <!-- Students -->
        <div class="form-group">
            <label for="students">Students (Comma-separated)</label>
            <input type="text" name="students" id="students" class="form-control" required>
        </div>

        <!-- Guide Name -->
        <div class="form-group">
            <label for="guide_name">Project Guide Name</label>
            <input type="text" name="guide_name" id="guide_name" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-4">Create Project</button>
    </form>
</div>
@endsection
