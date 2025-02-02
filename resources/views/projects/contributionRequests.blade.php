@extends('layouts.app')
@section('internalCSS')
<style>
    .form-group {
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


        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" required>
        </div>


        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>


        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
        </div>


        <input type="hidden" name="project_id" value="{{ $project->id }}">


        <div class="form-group">
            <label for="serialNumber">Serial Number</label>
            <input type="number" class="form-control" id="serialNumber" name="serialNumber" value="{{ $maxSerialNumber }}" required readonly>
        </div>


        <div class="form-group">
            <label for="year">Year</label>
            <input type="number" class="form-control" id="year" name="year" required>
        </div>


        <div class="form-group">
            <label for="dataset">Dataset Name</label>
            <input type="text" class="form-control" id="dataset" name="dataset" required>
        </div>


        <div class="form-group">
            <label for="kindOfTraffic">Kind of Traffic</label>
            <input type="text" class="form-control" id="kindOfTraffic" name="kindOfTraffic" required>
        </div>


        <div class="form-group">
            <label for="publicallyAvailable">Publically Available</label>
            <select class="form-control" id="publicallyAvailable" name="publicallyAvailable" required>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
        </div>


        <div class="form-group">
            <label for="countRecords">Count of Records</label>
            <input type="text" class="form-control" id="countRecords" name="countRecords" required>
        </div>


        <div class="form-group">
            <label for="featuresCount">Features Count</label>
            <input type="number" class="form-control" id="featuresCount" name="featuresCount" required>
        </div>

        <div class="form-group">
            <label for="citation_text">Citation Text</label>
            <textarea name="citation_text" id="citation_text" class="form-control" rows="4" required> </textarea>
        </div>

        <div class="form-group">
            <label for="cite">CITE</label>
            <textarea name="cite" id="cite" class="form-control" rows="4" required> </textarea>
        </div>

        <div class="form-group">
            <label for="citations">No. of citations</label>
            <input type="number" name="citations" id="citations" class="form-control" required>
        </div>


        <div class="form-group">
            <label for="doi">DOI</label>
            <input type="text" class="form-control" id="doi" name="doi">
        </div>


        <div class="form-group">
            <label for="downloadLinks">Download Links</label>
            <textarea class="form-control" id="downloadLinks" name="downloadLinks"></textarea>
        </div>


        <div class="form-group">
            <label for="abstract">Abstract</label>
            <textarea class="form-control" id="abstract" name="abstract" required></textarea>
        </div>


        <button type="submit" class="btn btn-success my-2">Submit Contribution</button>
    </form>
</div>
@endsection


