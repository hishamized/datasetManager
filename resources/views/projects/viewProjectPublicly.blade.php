@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Project Details</h1>


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

    <!-- Project Card -->
    <div class="card">
        <div class="card-header">
            <h3>{{ $project->title }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Description:</strong> {{ $project->description }}</p>
            <p><strong>Start Date:</strong> {{ $project->start_date }}</p>
            <p><strong>End Date:</strong> {{ $project->end_date }}</p>
            <p><strong>Guide Name:</strong> {{ $project->guide_name }}</p>
            <p><strong>Assigned Students:</strong> {{ $project->students }}</p>
        </div>
    </div>

    <form class="m-4" action="{{ route('projectDatasets.searchPublic', $project->id) }}" method="GET">
        @csrf
        <!-- Search Input -->
        <input type="text" name="search" placeholder="Search datasets..." class="form-control mb-2">

        <!-- Column Selection Dropdown -->
        <select name="column" class="form-control mb-2">
            <option value="all">All Columns</option>
            <option value="serialNumber">Serial Number</option>
            <option value="dataset">Dataset</option>
            <option value="year">Year</option>
            <option value="kindOfTraffic">Kind of Traffic</option>
            <option value="publicallyAvailable">Publicly Available</option>
            <option value="countRecords">Count of Records</option>
            <option value="featuresCount">Features Count</option>
            <option value="doi">DOI</option>
            <option value="downloadLinks">Download Links</option>
            <option value="abstract">Abstract</option>
        </select>

        <!-- Search Button -->
        <button type="submit" class="btn btn-primary">Search</button>
        <!-- Reset button -->
        <a href="{{ route('project.show.publicly', $project->id) }}" class="btn btn-danger">Reset</a>
    </form>


    <div class="table-responsive mt-4">
        <table class="table table-hover table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Serial Number</th>
                    <th scope="col">Dataset</th>
                    <th scope="col">Year</th>
                    <th scope="col">Kind of Traffic</th>
                    <th scope="col">Publically Available</th>
                    <th scope="col">Count of Records</th>
                    <th scope="col">Features Count</th>
                    <th scope="col">DOI</th>
                    <th scope="col">Download Links</th>
                    <th scope="col">Abstract</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datasets as $dataset)
                <tr id="dataset-row-{{ $dataset->id }}">
                    <td>{{ $dataset->serialNumber }}</td>
                    <td>
                        <div class="d-flex align-items-stretch">
                            <button class="btn btn-sm btn-lnk toggle-abstract" data-dataset="{{ $dataset->id }}" onclick="toggleAbstract('{{ $dataset->id }}')">
                                ⬇
                            </button>
                            <p> {{ $dataset->dataset }} </p>
                        </div>
                    </td>
                    <td>{{ $dataset->year }}</td>
                    <td>{{ $dataset->kindOfTraffic }}</td>
                    <td>{{ $dataset->publicallyAvailable ? 'Yes' : 'No' }}</td>
                    <td>{{ $dataset->countRecords }}</td>
                    <td>{{ $dataset->featuresCount }}</td>
                    <td><a class="btn btn-dark btn-sm" href="{{ $dataset->doi }}" target="_blank">DOI</a></td>
                    <td><a class="btn btn-info btn-sm" href="{{ $dataset->downloadLinks }}" target="_blank">Download</a></td>
                    <td>{{ Str::limit($dataset->abstract, 50) }} {{-- Limiting abstract to 50 chars --}}</td>
                </tr>

                {{-- Hidden row to display full abstract --}}
                <tr id="abstract-row-{{ $dataset->id }}" class="abstract-row" style="display: none;">
                    <td class="p-4" colspan="11">{{ $dataset->abstract }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


</div>

<script>
    function toggleAbstract(datasetId) {
        var abstractRow = document.getElementById('abstract-row-' + datasetId);
        var button = document.querySelector('[data-dataset="' + datasetId + '"]');

        if (abstractRow.style.display === 'none') {
            // Show the abstract row and change the button icon
            abstractRow.style.display = 'table-row';
            button.textContent = '⬆';
        } else {
            // Hide the abstract row and reset the button icon
            abstractRow.style.display = 'none';
            button.textContent = '⬇';
        }
    }
</script>
@endsection
