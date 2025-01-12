@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h2>Edit Project</h2>

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
    <form action="{{ route('project.update', $project->id) }}" method="POST">
        @csrf
        @method('PUT')


        <div class="form-group">
            <label for="title">Project Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $project->title ?? '') }}" required>
        </div>


        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $project->description ?? '') }}</textarea>
        </div>


        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $project->start_date ?? '') }}" required>
        </div>


        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $project->end_date ?? '') }}" required>
        </div>


        <div class="form-group">
            <label for="students">Students Involved</label>
            <textarea class="form-control" id="students" name="students" rows="2">{{ old('students', $project->students ?? '') }}</textarea>
        </div>


        <div class="form-group">
            <label for="guide_name">Guide Name</label>
            <input type="text" class="form-control" id="guide_name" name="guide_name" value="{{ old('guide_name', $project->guide_name ?? '') }}" required>
        </div>


        <button type="submit" class="btn btn-primary">Update Project</button>
    </form>
</div>

@endsection
