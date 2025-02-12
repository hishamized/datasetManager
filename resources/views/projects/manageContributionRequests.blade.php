@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2>Manage Contribution Requests for Project: {{ $project->name }}</h2>

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

    @if($project->contributionRequests->isEmpty())
    <p>No contribution requests found for this project.</p>
    @else

    <div class="table-responsive mt-4" style="overflow-x: auto;">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Serial Number</th>
                    <th>Year</th>
                    <th>Dataset</th>
                    <th>Kind of Traffic</th>
                    <th>Publically Available</th>
                    <th>Count of Records</th>
                    <th>Features Count</th>
                    <th>CITE</th>
                    <th>Number of citations</th>
                    <th>DOI</th>
                    <th>Download Links</th>
                    <th>Status</th>
                    <th>Submitted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($project->contributionRequests->sortBy(['status', 'created_at']) as $request)
                <tr>
                    <td>
                        <div class="d-flex align-items-stretch">
                            <button class="btn btn-sm btn-lnk" data-request="{{ $request->id }}" onclick="toggleAbstract('{{ $request->id }}')">
                            ⬇
                            </button>
                            <p>{{ $request->full_name }}</p>

                        </div>
                    </td>
                    <td>{{ $request->email }}</td>
                    <td>{{ $request->phone_number }}</td>
                    <td>{{ $request->serialNumber }}</td>
                    <td>{{ $request->year }}</td>
                    <td>{{ $request->dataset }}</td>
                    <td>{{ $request->kindOfTraffic }}</td>
                    <td>{{ $request->publicallyAvailable }}</td>
                    <td>{{ $request->countRecords }}</td>
                    <td>{{ $request->featuresCount }}</td>
                    <td>
                    <div class="d-flex flex-column gap-2">
                            <button class="btn btn-primary btn-sm" onclick="copyToClipboard(`{!! addslashes($request->cite) !!}`)">Copy</button>
                            <button class="btn btn-secondary btn-sm" onclick="downloadCitation(`{!! addslashes($request->cite) !!}`, 'citation_{{ $request->id }}.txt')">Download</button>
                        </div>
                    </td>
                    <td>{{ $request->citations }}</td>
                    <td> <a href="{{ $request->doi }}" class="btn btn-dark btn-sm">DOI</a>  </td>
                    <td><a href="{{ $request->downloadLinks }}" class="btn btn-success btn-sm">Download</a></td>
                    <td>
                        @if($request->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                        @elseif($request->status == 'accepted')
                        <span class="badge bg-success">Accepted</span>
                        @else
                        <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td>{{ $request->created_at }}</td>
                    <td>
                        @if($request->status == 'accepted')
                         <button disabled class="btn btn-dark btn-sm">Unavailable</button>
                        @else
                        <a href="{{ route('acceptContribution', $request->id) }}" class="btn btn-sm btn-success m-2">Accept</a>
                        <a href="{{ route('rejectContribution', $request->id) }}" class="btn btn-sm btn-danger  m-2">Reject</a>
                        <a href="{{ route('ignoreContribution', $request->id) }}" class="btn btn-sm btn-secondary  m-2">Ignore</a>
                        @endif
                    </td>
                </tr>

                <tr id="abstract-row-{{ $request->id }}" style="display: none;">
                    <td class="p-4" colspan="15">
                        <strong>Abstract:</strong> {{ $request->abstract }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<script>
    function toggleAbstract(id) {
       var button = document.querySelector('[data-request="' + id + '"]');
        var abstractRow = document.getElementById('abstract-row-' + id);
        if (abstractRow.style.display === "none") {
            abstractRow.style.display = "table-row";
            button.textContent = '⬆';
        } else {
            abstractRow.style.display = "none";
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

</script>
@endsection
