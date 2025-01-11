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

    @auth
    <div class="container">
        <button class="btn btn-primary mb-4" id="toggleFormBtn">Add Dataset</button>
         <a href="{{ route('manageContributionRequests', $project->id) }}" class="btn btn-danger mb-4">Contribution Requests</a>
    </div>
    <div id="datasetForm" style="display: none;">
        <form action="{{ route('dataset.store') }}" method="POST">
            @csrf

            <!-- Project ID (hidden field) -->
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <!-- Serial Number -->
            <div class="form-group">
                <label for="serialNumber">Serial Number</label>
                <input type="number" name="serialNumber" id="serialNumber" class="form-control" required>
            </div>

            <!-- Year -->
            <div class="form-group">
                <label for="year">Year</label>
                <input type="number" name="year" id="year" class="form-control" required>
            </div>

            <!-- Dataset -->
            <div class="form-group">
                <label for="dataset">Dataset</label>
                <input type="text" name="dataset" id="dataset" class="form-control" required>
            </div>

            <!-- Kind of Traffic -->
            <div class="form-group">
                <label for="kindOfTraffic">Kind of Traffic</label>
                <input type="text" name="kindOfTraffic" id="kindOfTraffic" class="form-control" required>
            </div>

            <!-- Publically Available -->
            <div class="form-group">
                <label for="publicallyAvailable">Publically Available</label>
                <select name="publicallyAvailable" id="publicallyAvailable" class="form-control" required>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>

            <!-- Count Records -->
            <div class="form-group">
                <label for="countRecords">Count of Records</label>
                <input type="text" name="countRecords" id="countRecords" class="form-control" required>
            </div>

            <!-- Features Count -->
            <div class="form-group">
                <label for="featuresCount">Features Count</label>
                <input type="number" name="featuresCount" id="featuresCount" class="form-control" required>
            </div>

            <!-- DOI -->
            <div class="form-group">
                <label for="doi">DOI</label>
                <input type="text" name="doi" id="doi" class="form-control" required>
            </div>

            <!-- Download Links -->
            <div class="form-group">
                <label for="downloadLinks">Download Links</label>
                <input type="text" name="downloadLinks" id="downloadLinks" class="form-control" required>
            </div>

            <!-- Abstract -->
            <div class="form-group">
                <label for="abstract">Abstract</label>
                <textarea name="abstract" id="abstract" class="form-control" rows="4" required></textarea>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-success mt-4">Add Dataset</button>
        </form>
    </div>
    @endauth
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
        @auth
        <div class="card-footer text-center">
            <a href="{{ route('project.edit', $project->id) }}" class="btn btn-warning btn-sm">Edit</a>

            <!-- Delete Button -->
            <form action="{{ route('project.destroy', $project->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this project?')">Delete</button>
            </form>
        </div>
        @endauth
    </div>

    <!-- Search section starts -->

    <!-- Search Form -->
    <form class="m-4" action="{{ route('projectDatasets.search', $project->id) }}" method="GET" class="mb-4">
        <div class="row">
            <!-- Search String Input -->
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Enter search string..." value="{{ request()->get('search') }}">
            </div>

            <!-- Column Select Input -->
            <div class="col-md-3">
                <select name="column" class="form-control">
                    <option value="all">All Columns</option>
                    <option value="serialNumber" {{ request()->get('column') == 'serialNumber' ? 'selected' : '' }}>Serial Number</option>
                    <option value="dataset" {{ request()->get('column') == 'dataset' ? 'selected' : '' }}>Dataset</option>
                    <option value="year" {{ request()->get('column') == 'year' ? 'selected' : '' }}>Year</option>
                    <option value="kindOfTraffic" {{ request()->get('column') == 'kindOfTraffic' ? 'selected' : '' }}>Kind of Traffic</option>
                    <option value="publicallyAvailable" {{ request()->get('column') == 'publicallyAvailable' ? 'selected' : '' }}>Publically Available</option>
                    <option value="countRecords" {{ request()->get('column') == 'countRecords' ? 'selected' : '' }}>Count of Records</option>
                    <option value="featuresCount" {{ request()->get('column') == 'featuresCount' ? 'selected' : '' }}>Features Count</option>
                    <option value="doi" {{ request()->get('column') == 'doi' ? 'selected' : '' }}>DOI</option>
                    <option value="downloadLinks" {{ request()->get('column') == 'downloadLinks' ? 'selected' : '' }}>Download Links</option>
                    <option value="abstract" {{ request()->get('column') == 'abstract' ? 'selected' : '' }}>Abstract</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="d-flex col-md-1">
                <button type="submit" class="btn btn-primary mx-2">Search</button>
                <!-- Reset button -->
                <a href="{{ route('project.show', $project->id) }}" class="btn btn-danger mx-2">Reset</a>
            </div>
        </div>
    </form>
    <!-- Search section ends -->

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
                    <th scope="col">Actions</th>
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
                    <td>
                        @auth
                        <a href="{{ route('showEditDataset', $dataset->id) }}" class="btn btn-warning btn-sm my-2">Edit</a>
                        <form action="{{ route('deleteDataset', $dataset->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm my-2" onclick="return confirm('Are you sure you want to delete this dataset?')">Delete</button>
                        </form>
                        @endauth
                    </td>
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
    document.getElementById('toggleFormBtn').addEventListener('click', function() {
        var form = document.getElementById('datasetForm');
        if (form.style.display === 'none') {
            form.style.display = 'block';
            this.textContent = 'Hide Dataset Form';
        } else {
            form.style.display = 'none';
            this.textContent = 'Add Dataset';
        }
    });
</script>

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
