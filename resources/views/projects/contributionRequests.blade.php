@extends('layouts.app')
@section('internalCSS')
<style>
    .form-group{
        margin: 20px 0;
    }
</style>
@endsection
@section('content')
<div class="container">
    <h1 class="my-4">Contribute to Project</h1>


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

    <form action="{{ route('contribution.submit') }}" method="POST">
        @csrf

        <!-- Full Name -->
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <!-- Phone Number -->
        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
        </div>

        <!-- Project ID (Hidden) -->
        <input type="hidden" name="project_id" value="{{ $project->id }}">

        <!-- Serial Number -->
        <div class="form-group">
            <label for="serialNumber">Serial Number</label>
            <input type="number" class="form-control" id="serialNumber" name="serialNumber" required>
        </div>

        <!-- Year -->
        <div class="form-group">
            <label for="year">Year</label>
            <input type="number" class="form-control" id="year" name="year" required>
        </div>

        <!-- Dataset -->
        <div class="form-group">
            <label for="dataset">Dataset Name</label>
            <input type="text" class="form-control" id="dataset" name="dataset" required>
        </div>

        <!-- Kind of Traffic -->
        <div class="form-group">
            <label for="kindOfTraffic">Kind of Traffic</label>
            <input type="text" class="form-control" id="kindOfTraffic" name="kindOfTraffic" required>
        </div>

        <!-- Publically Available -->
        <div class="form-group">
            <label for="publicallyAvailable">Publically Available</label>
            <select class="form-control" id="publicallyAvailable" name="publicallyAvailable" required>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
        </div>

        <!-- Count of Records -->
        <div class="form-group">
            <label for="countRecords">Count of Records</label>
            <input type="text" class="form-control" id="countRecords" name="countRecords" required>
        </div>

        <!-- Features Count -->
        <div class="form-group">
            <label for="featuresCount">Features Count</label>
            <input type="number" class="form-control" id="featuresCount" name="featuresCount" required>
        </div>

        <!-- DOI -->
        <div class="form-group">
            <label for="doi">DOI (optional)</label>
            <input type="text" class="form-control" id="doi" name="doi">
        </div>

        <!-- Download Links -->
        <div class="form-group">
            <label for="downloadLinks">Download Links (optional)</label>
            <textarea class="form-control" id="downloadLinks" name="downloadLinks"></textarea>
        </div>

        <!-- Abstract -->
        <div class="form-group">
            <label for="abstract">Abstract</label>
            <textarea class="form-control" id="abstract" name="abstract" required></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-success">Submit Contribution</button>
    </form>
</div>
@endsection
