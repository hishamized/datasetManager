@extends('layouts.app')


@section('internalCSS')
<style>
    .form-group {
        margin: 15px 0px;
    }

    #customAttributesList {
        display: flex;
        flex-direction: column;
        row-gap: 15px;
    }

    .attribute-row {
        display: flex;
        flex-direction: row;
        column-gap: 10px;
        justify-content: space-between;
        width: fit-content;
    }
</style>
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

    @auth
    <div class="container">
        <button class="btn btn-primary mb-4" id="toggleFormBtn">Add Dataset</button>
        <a href="{{ route('manageContributionRequests', $project->id) }}" class="btn btn-danger mb-4">Contribution Requests</a>
    </div>
    <div id="datasetForm" style="display: none;">
        <form action="{{ route('dataset.store') }}" method="POST">
            @csrf


            <input type="hidden" name="project_id" value="{{ $project->id }}">


            <div class="form-group">
                <label for="serialNumber">Serial Number</label>
                <input type="number" name="serialNumber" id="serialNumber" class="form-control" value="{{ $maxSerialNumber }}" required readonly>
            </div>


            <div class="form-group">
                <label for="year">Year</label>
                <input type="number" name="year" id="year" class="form-control" required>
            </div>


            <div class="form-group">
                <label for="dataset">Dataset</label>
                <input type="text" name="dataset" id="dataset" class="form-control" required>
            </div>


            <div class="form-group">
                <label for="kindOfTraffic">Kind of Traffic</label>
                <input type="text" name="kindOfTraffic" id="kindOfTraffic" class="form-control" required>
            </div>


            <div class="form-group">
                <label for="publicallyAvailable">Publically Available</label>
                <select name="publicallyAvailable" id="publicallyAvailable" class="form-control" required>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>


            <div class="form-group">
                <label for="countRecords">Count of Records</label>
                <input type="text" name="countRecords" id="countRecords" class="form-control" required>
            </div>


            <div class="form-group">
                <label for="featuresCount">Features Count</label>
                <input type="number" name="featuresCount" id="featuresCount" class="form-control" required>
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
                <input type="text" name="doi" id="doi" class="form-control" required>
            </div>


            <div class="form-group">
                <label for="downloadLinks">Download Links</label>
                <input type="text" name="downloadLinks" id="downloadLinks" class="form-control" required>
            </div>


            <div class="form-group">
                <label for="abstract">Abstract</label>
                <textarea name="abstract" id="abstract" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="customAttributesToggle">
                <label class="form-check-label" for="customAttributesToggle">Add Custom Attributes</label>
            </div>

            <div class="customAttributesContainer" id="customAttributes" style="display: none;">
                <button type="button" class="btn btn-primary my-2" id="addAttributeButton">Add Attribute</button>
                <div id="customAttributesList"></div>
            </div>


            <button type="submit" class="btn btn-success mt-4 my-2">Add Dataset</button>
        </form>
    </div>
    @endauth

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


            <form action="{{ route('project.destroy', $project->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this project?')">Delete</button>
            </form>
        </div>
        @endauth
    </div>




    <form class="m-4" action="{{ route('projectDatasets.search', $project->id) }}" method="GET" class="mb-4">
        <div class="row">

            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Enter search string..." value="{{ request()->get('search') }}">
            </div>


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
                    <option value="citation_text" {{ request()->get('column') == 'citation_text' ? 'selected' : '' }}>Citation Text</option>
                    <option value="cite" {{ request()->get('column') == 'cite' ? 'selected' : '' }}>Cite</option>
                    <option value="doi" {{ request()->get('column') == 'doi' ? 'selected' : '' }}>DOI</option>
                    <option value="downloadLinks" {{ request()->get('column') == 'downloadLinks' ? 'selected' : '' }}>Download Links</option>
                    <option value="abstract" {{ request()->get('column') == 'abstract' ? 'selected' : '' }}>Abstract</option>
                </select>
            </div>


            <div class="d-flex col-md-1">
                <button type="submit" class="btn btn-primary mx-2">Search</button>

                <a href="{{ route('project.show', $project->id) }}" class="btn btn-danger mx-2">Reset</a>
            </div>
        </div>
    </form>


    <div class="table-responsive mt-4">
        @if($datasets->isEmpty())
        <div class="alert alert-info">
            No datasets available for this project.
        </div>
        @else
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
                    <th scope="col">Citation Text</th>
                    <th scope="col">CITE</th>
                    <th scope="col">
                        <div class="d-flex flex-row gap-2">
                            Citations
                            <strong class="text-danger">
                                (*)
                            </strong>
                        </div>
                    </th>
                    <th scope="col">DOI</th>
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
                            <button class="btn btn-primary btn-sm" onclick="copyToClipboard(`{!! addslashes($dataset->citation_text) !!}`)">Copy</button>
                            <button class="btn btn-secondary btn-sm" onclick="downloadCitation(`{!! addslashes($dataset->citation_text) !!}`, 'citation_{{ $dataset->id }}.txt')">Download</button>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-2">
                            <button class="btn btn-primary btn-sm" onclick="copyToClipboard(`{!! addslashes($dataset->cite) !!}`)">Copy</button>
                            <button class="btn btn-secondary btn-sm" onclick="downloadCitation(`{!! addslashes($dataset->cite) !!}`, 'cite_{{ $dataset->id }}.txt')">Download</button>
                        </div>
                    </td>
                    <td>{{ $dataset->citations }}</td>
                    <td><a class="btn btn-dark btn-sm" href="{{ $dataset->doi }}" target="_blank">DOI</a></td>
                    <td><a class="btn btn-info btn-sm" href="{{ $dataset->downloadLinks }}" target="_blank">Download</a></td>
                    <td>
                        @auth
                        <a href="{{ route('dataset-details', $dataset->id) }}" class="btn btn-success btn-sm my-2">Dataset Overview</a>
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
        <div class="d-flex flex-row justify-content-end align-content-center p-2">
            <strong class="text-danger">(*)</strong>
            <p>Total Citations as of {{ \Carbon\Carbon::now()->format('F Y') }} </p>
        </div>
        @endif
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

            abstractRow.style.display = 'table-row';
            button.textContent = '⬆';
        } else {

            abstractRow.style.display = 'none';
            button.textContent = '⬇';
        }
    }

    function copyToClipboard(text) {
        const tempInput = document.createElement('textarea');
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        alert('Citation text copied to clipboard!');
    }



    function downloadCitation(text, filename) {

        const blob = new Blob([text], {
            type: "text/plain"
        });

        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = filename;

        link.click();

        URL.revokeObjectURL(link.href);
    }


    document.addEventListener("DOMContentLoaded", function() {
        const customAttributesToggle = document.getElementById("customAttributesToggle");
        const customAttributesDiv = document.getElementById("customAttributes");
        const addAttributeButton = document.getElementById("addAttributeButton");
        const customAttributesList = document.getElementById("customAttributesList");
        let attributeCount = 0;


        customAttributesToggle.addEventListener("change", function() {
            if (customAttributesToggle.checked) {
                customAttributesDiv.style.display = "block";
            } else {
                customAttributesDiv.style.display = "none";
                customAttributesList.innerHTML = '';
                attributeCount = 0;
            }
        });


        addAttributeButton.addEventListener("click", function() {
            if (attributeCount < 5) {
                const attributeRow = document.createElement("div");
                attributeRow.classList.add("form-row", "mb-2", "attribute-row");

                const keyInput = document.createElement("input");
                keyInput.classList.add("form-control", "col-5");
                keyInput.name = `custom_attributes[${attributeCount}][key]`;
                keyInput.placeholder = "Attribute Name";
                keyInput.required = true;

                const valueInput = document.createElement("input");
                valueInput.classList.add("form-control", "col-5");
                valueInput.name = `custom_attributes[${attributeCount}][value]`;
                valueInput.placeholder = "Attribute Value";
                valueInput.required = true;

                const removeButton = document.createElement("button");
                removeButton.classList.add("btn", "btn-danger", "col-2");
                removeButton.type = "button";
                removeButton.innerText = "X";
                removeButton.onclick = function() {
                    customAttributesList.removeChild(attributeRow);
                    attributeCount--;
                };

                attributeRow.appendChild(keyInput);
                attributeRow.appendChild(valueInput);
                attributeRow.appendChild(removeButton);

                customAttributesList.appendChild(attributeRow);
                attributeCount++;
            } else {
                alert("You can add a maximum of 5 custom attributes.");
            }
        });
    });
</script>
@endsection
