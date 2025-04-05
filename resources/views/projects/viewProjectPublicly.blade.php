@extends('layouts.app')
@section('externalCSS')
<link rel="stylesheet" href="{{ asset('css/viewProjectPublicly.css') }}">
@endsection

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

    <div class="container">

        <div id="sideMenu">
            <ul class="nav flex-column p-3 text-white">
                <li class="nav-item">
                    <a class="nav-link text-white" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('makeContributionRequest', $project->id) }}" class="nav-link text-white">Contribute</a>
                </li>
            </ul>
        </div>


        <div id="menuOverlay"></div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-row justify-content-between align-items-center">
            <h3>{{ $project->title }}</h3>

            <div id="menuButton" class="p-2" style="z-index: 1001;">
                <span class="hamburger">&#9776;</span>
                <span class="cross">&times;</span>
            </div>
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

        <input type="text" name="search" placeholder="Search datasets..." class="form-control mb-2">


        <select name="column" class="form-control mb-2">
            <option value="all">All Columns</option>
            <option value="serialNumber">Serial Number</option>
            <option value="dataset">Dataset</option>
            <option value="year">Year</option>
            <option value="kindOfTraffic">Kind of Traffic</option>
            <option value="publicallyAvailable">Publicly Available</option>
            <option value="countRecords">Count of Records</option>
            <option value="featuresCount">Features Count</option>
            <option value="cite">Cite</option>
            <option value="attackType">Attack Type</option>
            <option value="downloadLinks">Download Links</option>
            <option value="abstract">Abstract</option>
        </select>


        <button type="submit" class="btn btn-primary">Search</button>

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
                    <th scope="col">CITE</th>
                    <th scope="col">
                        <div class="d-flex flex-row gap-2">
                            Citations
                            <strong class="text-danger">
                                (*)
                            </strong>
                        </div>
                    </th>
                    <th scope="col">Attack Type</th>
                    <th scope="col">Download Links</th>
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
                    <td>
                    <div class="d-flex flex-column gap-2">
                            <button class="btn btn-primary btn-sm" onclick="copyToClipboard(`{!! addslashes($dataset->cite) !!}`)">Copy</button>
                            <button class="btn btn-secondary btn-sm" onclick="downloadCitation(`{!! addslashes($dataset->cite) !!}`, 'cite_{{ $dataset->id }}.bib')">Download</button>
                        </div>
                    </td>
                    <td>{{ $dataset->citations }}</td>
                    <td>{{ $dataset->attackType }}</td>
                    <td><a class="btn btn-info btn-sm" href="{{ $dataset->downloadLinks }}" target="_blank">Download</a></td>

                    <td>
                        <a href="{{ route('showDatasetDetailsPublicly', $dataset->id) }}" class="btn btn-primary btn-sm">Dataset Overview</a>
                    </td>
                </tr>

                {{-- Hidden row to display full abstract --}}
                <tr id="abstract-row-{{ $dataset->id }}" class="abstract-row" style="display: none;">
                    <td class="p-4" colspan="11">{{ $dataset->abstract }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex flex-row justify-content-end align-content-center p-2">
            <strong class="text-danger">(*)</strong>
            <p>Total Citations as of {{ \Carbon\Carbon::now()->format('F Y') }} </p>
        </div>
    </div>


</div>

<script>
    function toggleAbstract(datasetId) {
        var abstractRow = document.getElementById('abstract-row-' + datasetId);
        var button = document.querySelector('[data-dataset="' + datasetId + '"]');

        if (abstractRow.style.display === 'none') {

            abstractRow.style.display = 'table-row';
            button.textContent = '⬆';
        } else {

            abstractRow.style.display = 'none';
            button.textContent = '⬇';
        }
    }

    function copyToClipboard(text) {

        const textarea = document.createElement("textarea");
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);
        alert("Citation text copied to clipboard!");
    }


    function downloadCitation(text, filename) {
    const blob = new Blob([text], {
        type: "application/x-bibtex"
    });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
    URL.revokeObjectURL(link.href);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var $sideMenu = $('#sideMenu');
        var $menuButton = $('#menuButton');
        var $hamburger = $menuButton.find('.hamburger');
        var $cross = $menuButton.find('.cross');
        var $overlay = $('#menuOverlay');

        // Show the menu and toggle icons
        $menuButton.on('click', function() {
            if ($sideMenu.hasClass('show')) {
                hideMenu();
            } else {
                showMenu();
            }
        });

        // Hide the menu when clicking outside of it
        $overlay.on('click', function() {
            hideMenu();
        });

        function showMenu() {
            $sideMenu.addClass('show');
            $hamburger.hide();
            $cross.show();
            $overlay.show();
        }

        function hideMenu() {
            $sideMenu.removeClass('show');
            $hamburger.show();
            $cross.hide();
            $overlay.hide();
        }
    });
</script>
@endsection
