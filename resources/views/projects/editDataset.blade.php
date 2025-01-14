@extends('layouts.app')

@section('content')
<div class="container m-4">
    <h2>Edit Dataset</h2>


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

    <form action="{{ route('updateDataset', $dataset->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group m-2">
            <label for="serialNumber">Serial Number</label>
            <input type="number" class="form-control" id="serialNumber" name="serialNumber" value="{{ old('serialNumber', $dataset->serialNumber ?? '') }}" required>
        </div>

        <div class="form-group m-2">
            <label for="year">Year</label>
            <input type="number" class="form-control" id="year" name="year" value="{{ old('year', $dataset->year ?? '') }}" required>
        </div>

        <div class="form-group m-2">
            <label for="dataset">Dataset Name</label>
            <input type="text" class="form-control" id="dataset" name="dataset" value="{{ old('dataset', $dataset->dataset ?? '') }}" required>
        </div>

        <div class="form-group m-2">
            <label for="kindOfTraffic">Kind of Traffic</label>
            <input type="text" class="form-control" id="kindOfTraffic" name="kindOfTraffic" value="{{ old('kindOfTraffic', $dataset->kindOfTraffic ?? '') }}" required>
        </div>

        <div class="form-group m-2">
            <label for="publicallyAvailable">Publically Available</label>
            <select class="form-control" id="publicallyAvailable" name="publicallyAvailable" required>
                <option value="yes" {{ (old('publicallyAvailable', $dataset->publicallyAvailable ?? '') == 'yes') ? 'selected' : '' }}>Yes</option>
                <option value="no" {{ (old('publicallyAvailable', $dataset->publicallyAvailable ?? '') == 'no') ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="form-group m-2">
            <label for="countRecords">Count of Records</label>
            <input type="text" class="form-control" id="countRecords" name="countRecords" value="{{ old('countRecords', $dataset->countRecords ?? '') }}" required>
        </div>

        <div class="form-group m-2">
            <label for="featuresCount">Features Count</label>
            <input type="number" class="form-control" id="featuresCount" name="featuresCount" value="{{ old('featuresCount', $dataset->featuresCount ?? '') }}" required>
        </div>

        <div class="form-group m-2">
            <label for="classesCount">No. of citations</label>
            <input type="number" class="form-control" id="citations" name="citations" value="{{ old('citations', $dataset->citations ?? '') }}" required>
        </div>

        <div class="form-group m-2">
            <label for="doi">DOI (Optional)</label>
            <input type="text" class="form-control" id="doi" name="doi" value="{{ old('doi', $dataset->doi ?? '') }}">
        </div>

        <div class="form-group m-2">
            <label for="downloadLinks">Download Links (Optional)</label>
            <textarea class="form-control" id="downloadLinks" name="downloadLinks">{{ old('downloadLinks', $dataset->downloadLinks ?? '') }}</textarea>
        </div>

        <div class="form-group m-2">
            <label for="abstract">Abstract</label>
            <textarea class="form-control" id="abstract" name="abstract" rows="5" required>{{ old('abstract', $dataset->abstract ?? '') }}</textarea>
        </div>


        <div class="form-group">
            <label for="customAttributes">Custom Attributes</label>


            @if($dataset->custom_attributes)
            @php
            $customAttributes = json_decode($dataset->custom_attributes, true);
            @endphp


            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Attribute Name</th>
                        <th scope="col">Attribute Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customAttributes as $index => $attribute)
                    <tr>
                        <td>
                            <input type="text" name="custom_attributes[{{ $index }}][key]" class="form-control" value="{{ $attribute['key'] }}" required>
                        </td>
                        <td>
                            <input type="text" name="custom_attributes[{{ $index }}][value]" class="form-control" value="{{ $attribute['value'] }}" required>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @else
            <p>No custom attributes available.</p>
            @endif
        </div>



        <button type="submit" class="btn btn-primary m-2">Update Dataset</button>
    </form>
</div>
@endsection
