@extends('layouts.app')
@section('externalCSS')
<link rel="stylesheet" href="{{ asset('css/landingNew.css') }}">
@endsection

@section('content')
<div class="container">
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
                <div class="logo">
                    <img src="{{ asset('shield.png') }}" alt="Logo" class="img-fluid">
                </div>
                <div class="app-name">IDS Datasets</div>
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

    <form class="m-4" action="{{ route('searchLandingNew', $project->id) }}" method="get">
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

        <a href="/" class="btn btn-danger">Reset</a>
    </form>
    <button class="btn btn-success my-3" onclick="exportTableToExcel('datasets-table')">Download as Excel</button>
    <div class="table-responsive mt-4">
        <table class="table table-hover table-bordered table-striped" id="datasets-table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" class="text-nowrap">Serial Number</th>
                    <th scope="col" class="text-nowrap">Dataset</th>
                    <th scope="col" class="text-nowrap">Year</th>
                    <th scope="col" class="text-nowrap">Kind of Traffic</th>
                    <th scope="col" class="text-nowrap">Publically Available</th>
                    <th scope="col" class="text-nowrap">Count of Records</th>
                    <th scope="col" class="text-nowrap">Features Count</th>
                    <th scope="col" class="text-nowrap">CITE</th>
                    <th scope="col" class="text-nowrap">
                        <div class="d-flex flex-row gap-2">
                            Citations
                            <strong class="text-danger">(*)</strong>
                        </div>
                    </th>
                    <th scope="col" class="text-nowrap">Attack Type</th>
                    <th scope="col" class="text-nowrap">Download Links</th>
                    <th scope="col" class="no-export text-nowrap">Actions</th>
                </tr>

            </thead>
            <tbody>
                @foreach($datasets as $dataset)
                <tr id="dataset-row-{{ $dataset->id }}">
                    <td>{{ $dataset->serialNumber }}</td>
                    <td>
                        <div class="d-flex align-items-stretch">
                            <button class="btn btn-sm btn-lnk toggle-abstract" data-dataset="{{ $dataset->id }}" onclick="toggleAbstract('{{ $dataset->id }}')">
                            <i class="bi bi-arrow-bar-down"></i>
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
                        <span class="cite-text d-none">{{ $dataset->cite }}</span>
                        <div class="d-flex flex-column gap-2 d-print-none">
                            <button title="Copy" class="btn btn-primary btn-sm" onclick="copyToClipboard(`{!! addslashes($dataset->cite) !!}`)">
                            <i class="bi bi-clipboard2-check-fill"></i>
                            </button>
                            <button title="Download" class="btn btn-secondary btn-sm" onclick="downloadCitation(`{!! addslashes($dataset->cite) !!}`, 'cite_{{ $dataset->id }}.bib')">
                            <i class="bi bi-file-earmark-arrow-down-fill"></i>
                            </button>
                        </div>
                    </td>

                    <td>{{ $dataset->citations }}</td>
                    <td>{{ $dataset->attackType }}</td>
                    <td>
                        <span class="download-link d-none">{{ $dataset->downloadLinks }}</span>
                        <a title="Download Dataset" class="btn btn-info btn-sm d-print-none" href="{{ $dataset->downloadLinks }}" target="_blank">
                        <i class="bi bi-cloud-arrow-down-fill"></i>
                        </a>
                    </td>


                    <td class="no-export">
                        <a title="View Dataset" href="{{ route('showDatasetDetailsPublicly', $dataset->id) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-eye-fill"></i>
                        </a>
                    </td>
                </tr>

                {{-- Hidden row to display full abstract --}}
                <tr id="abstract-row-{{ $dataset->id }}" class="abstract-row" style="display: none;">
                    <td class="p-4" colspan="11">{{ $dataset->abstract }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex flex-row justify-content-start align-content-start p-2">
            <strong class="text-danger">(*)</strong>
            <p>Total Citations as of {{ \Carbon\Carbon::now()->format('F Y') }} </p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row d-flex align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 text-primary">Intrusion Detection System Datasets</h1>
                <p class="lead">
                    The Intrusion Detection System (IDS) project in network security focuses on identifying and mitigating
                    unauthorized access and cyber threats within networks. This home page serves as the central hub for managing
                    datasets related to this project, giving you the tools to efficiently organize, analyze, and track data
                    pertaining to intrusion detection. Explore the features to streamline your dataset management.
                </p>
            </div>

            <div class="col-md-6 text-center">
                <img src="{{ asset('shield.png') }}" alt="IDS Logo" class="img-fluid mb-3" style="max-width: 40%;">
                <h3 class="text-secondary">IDS - DATASETS</h3>

                <div class="btn-group mt-4">
                    <a href="{{ route('user.login') }}" class="btn btn-outline-primary">Dashboard</a>
                    @auth
                    <a href="{{ route('project.show', ['id' => $project->id ]) }}" class="btn btn-outline-info">Manage Datasets</a>
                    @endauth
                    <a href="{{ route('makeContributionRequest', $project->id) }}" class="btn btn-outline-info">Contribute</a>
                    <a href="#" id="settings" class="btn btn-outline-success">Settings</a>
                </div>
            </div>
        </div>

        <!-- Menu Button
    <div id="menuButton" style="z-index: 1001; right: 20px; top: 20px;">
        <span class="hamburger" style="font-size: 2rem;">&#9776;</span>
        <span class="cross" style="font-size: 2rem; display: none;">&times;</span>
    </div>
-->
    </div>
</div>

<script>
    function toggleAbstract(datasetId) {
        var abstractRow = document.getElementById('abstract-row-' + datasetId);
        var button = document.querySelector('[data-dataset="' + datasetId + '"]');

        if (abstractRow.style.display === 'none') {

            abstractRow.style.display = 'table-row';
            button.innerHTML = '<i class="bi bi-arrow-bar-up"></i>';
        } else {

            abstractRow.style.display = 'none';
            button.innerHTML = '<i class="bi bi-arrow-bar-down"></i>';
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


    function exportTableToExcel(tableId) {
        var table = document.getElementById(tableId);
        const citeCells = table.querySelectorAll('td');
        citeCells.forEach(cell => {
            const citeText = cell.querySelector('.cite-text');
            if (citeText) {
                cell.innerText = citeText.innerText;
            }

            const downloadLink = cell.querySelector('.download-link');
            if (downloadLink) {
                cell.innerText = downloadLink.innerText;
            }
        });

        const allRows = table.querySelectorAll("tr.abstract-row");
        allRows.forEach(row => row.remove());

        const clonedTable = table.cloneNode(true);

        clonedTable.querySelectorAll(".no-export").forEach(el => el.remove());

        var workbook = XLSX.utils.table_to_book(clonedTable, {
            sheet: "Datasets"
        });
        XLSX.writeFile(workbook, "datasets.xlsx");
    }
</script>

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
    $(document).ready(function() {
        var $sideMenu = $('#sideMenu');
        var $menuButton = $('#menuButton');
        var $hamburger = $menuButton.find('.hamburger');
        var $cross = $menuButton.find('.cross');
        var $overlay = $('#menuOverlay');
        var $settings = $('#settings');

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

        $settings.on('click', function() {
            if ($sideMenu.hasClass('show')) {
                hideMenu();
            } else {
                showMenu();
            }
        });
    });
</script>
@endsection
