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


    @if ($projects->isEmpty())
    <div class="alert alert-info">
        You haven't created any projects yet.
    </div>
    @else
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
                    <a href="{{ route('project.show.publicly', $project->id) }}" class="btn btn-info btn-sm">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
