@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Dataset Details Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Dataset Details: {{ $dataset->dataset }}</h4>
                </div>
                <div class="card-body">

                    <!-- Project and Owner Information -->
                    <!--
                    <div class="mb-3">
                        <h5 class="card-title">Project Information</h5>
                        <p><strong>Project Name:</strong> {{ $dataset->project->title ?? 'N/A' }}</p>
                        <p><strong>Owner Name:</strong> {{ $dataset->project->user->fullName ?? 'N/A' }}</p>
                        <p><strong>Guide Name:</strong> {{ $dataset->project->guide_name ?? 'N/A' }}</p>
                    </div>
-->

                    <!-- Dataset Information -->
                    <h5 class="card-title">Dataset Information</h5>
                    <p><strong>Serial Number:</strong> {{ $dataset->serialNumber }}</p>
                    <p><strong>Year:</strong> {{ $dataset->year }}</p>
                    <p><strong>Kind of Traffic:</strong> {{ $dataset->kindOfTraffic }}</p>
                    <p><strong>Publicly Available:</strong> {{ ucfirst($dataset->publicallyAvailable) }}</p>
                    <p><strong>Count of Records:</strong> {{ $dataset->countRecords }}</p>
                    <p><strong>Features Count:</strong> {{ $dataset->featuresCount }}</p>
                    <!-- Citation Text Section -->
                    <div class="mb-4">
                        <h5 class="card-title">Citation Text</h5>
                        <div class="d-flex flex-column gap-2">
                            <button class="btn btn-primary btn-sm" onclick="copyToClipboard(`{!! addslashes($dataset->citation_text) !!}`)">Copy</button>
                            <button class="btn btn-secondary btn-sm" onclick="downloadCitation(`{!! addslashes($dataset->citation_text) !!}`, 'citation_{{ $dataset->id }}.txt')">Download</button>
                        </div>
                    </div>

                    <!-- CITE Section -->
                    <div class="mb-4">
                        <h5 class="card-title">CITE</h5>
                        <div class="d-flex flex-column gap-2">
                            <button class="btn btn-primary btn-sm" onclick="copyToClipboard(`{!! addslashes($dataset->cite) !!}`)">Copy</button>
                            <button class="btn btn-secondary btn-sm" onclick="downloadCitation(`{!! addslashes($dataset->cite) !!}`, 'cite_{{ $dataset->id }}.txt')">Download</button>
                        </div>
                    </div>
                    <p><strong>No. of citations:</strong> {{ $dataset->citations }}</p>
                    <p><strong>DOI:</strong> {{ $dataset->doi ?? 'N/A' }}</p>

                    <!-- Download Links -->
                    @if($dataset->downloadLinks)
                    <p><strong>Download Links:</strong> <a href="{{ $dataset->downloadLinks }}" target="_blank">{{ $dataset->downloadLinks }}</a></p>
                    @else
                    <p><strong>Download Links:</strong> Not Available</p>
                    @endif

                    <!-- Abstract -->
                    <p><strong>Abstract:</strong> {{ $dataset->abstract }}</p>

                    <!-- Custom Attributes -->
                    <!--
                    @if($dataset->custom_attributes)
                    @php
                    $customAttributes = json_decode($dataset->custom_attributes, true);
                    @endphp
                    <h5 class="card-title">Custom Attributes</h5>
                    <ul class="list-group mb-3">
                        @foreach($customAttributes as $attribute)
                        <li class="list-group-item">
                            <strong>{{ $attribute['key'] }}:</strong> {{ $attribute['value'] }}
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p><strong>Custom Attributes:</strong> None</p>
                    @endif
-->

                </div>
            </div>

            @auth
            <a href="{{ route('project.show', $dataset->project->id) }}" class="btn btn-secondary my-2">Back to Datasets</a>
            @endauth

            @guest
            <a href="{{ route('project.show.publicly', $dataset->project->id) }}" class="btn btn-secondary my-2">Back to Datasets</a>
            @endguest

        </div>
    </div>
</div>

<script>
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
            type: "text/plain"
        });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
        URL.revokeObjectURL(link.href);
    }
</script>

@endsection
