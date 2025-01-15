@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Your Projects</h1>

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

    <form class="m-4" action="{{ route('projects.search') }}" method="POST" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Enter search string..." value="{{ request()->get('search') }}">
            </div>
            <div class="col-md-3">
                <select name="column" class="form-control">
                    <option value="all">All Columns</option>
                    <option value="title" {{ request()->get('column') == 'title' ? 'selected' : '' }}>Project Title</option>
                    <option value="description" {{ request()->get('column') == 'description' ? 'selected' : '' }}>Description</option>
                    <option value="start_date" {{ request()->get('column') == 'start_date' ? 'selected' : '' }}>Start Date</option>
                    <option value="end_date" {{ request()->get('column') == 'end_date' ? 'selected' : '' }}>End Date</option>
                    <option value="students" {{ request()->get('column') == 'students' ? 'selected' : '' }}>Students</option>
                    <option value="guide_name" {{ request()->get('column') == 'guide_name' ? 'selected' : '' }}>Guide Name</option>
                </select>
            </div>
            <div class="d-flex col-md-1">
                <button type="submit" class="btn btn-primary mx-2">Search</button>
                <a href="{{ route('projects.view') }}" class="btn btn-danger mx-2">Reset</a>
            </div>
        </div>
    </form>

    @if ($projects->isEmpty())
    <div class="alert alert-info">
        You haven't created any projects yet.
    </div>
    @else
    <div class="table-responsive mt-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Project Title</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Students</th>
                    <th>Guide Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                <tr>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->description }}</td>
                    <td>{{ $project->start_date }}</td>
                    <td>{{ $project->end_date }}</td>
                    <td>{{ $project->students }}</td>
                    <td>{{ $project->guide_name }}</td>
                    <td>
                        @auth
                        <a href="{{ route('project.edit', $project->id) }}" class="btn btn-warning btn-sm my-2">Edit</a>
                        <a href="{{ route('project.show', $project->id) }}" class="btn btn-info btn-sm my-2">View</a>

                        <form class="my-2" action="{{ route('project.destroy', $project->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm my-2" onclick="return confirm('Are you sure you want to delete this project?')">Delete</button>
                        </form>
                        @endauth
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
