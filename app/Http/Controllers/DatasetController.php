<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Dataset;
use Illuminate\Support\Facades\Validator;
use App\Models\Project;
use App\Models\User;
use App\Models\ContributionRequest;


class DatasetController extends Controller
{
    public function storeDataset(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'serialNumber' => 'required|numeric',
            'year' => 'required|numeric',
            'dataset' => 'required|string|max:255',
            'kindOfTraffic' => 'required|string|max:255',
            'publicallyAvailable' => 'required|in:yes,no',
            'countRecords' => 'required|string|max:255',
            'featuresCount' => 'required|string|max:255',
            'cite' => 'required|string',
            'citations' => 'required|numeric',
            'attackType' => 'required|string|max:255',
            'downloadLinks' => 'required|string|max:255',
            'abstract' => 'required|string',
            'custom_attributes' => 'nullable|array',
        ]);

        $customAttributes = $request->input('custom_attributes', []);
        $encodedAttributes = json_encode($customAttributes);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {

            Dataset::create([
                'project_id' => $request->project_id,
                'serialNumber' => $request->serialNumber,
                'year' => $request->year,
                'dataset' => $request->dataset,
                'kindOfTraffic' => $request->kindOfTraffic,
                'publicallyAvailable' => $request->publicallyAvailable,
                'countRecords' => $request->countRecords,
                'featuresCount' => $request->featuresCount,
                'attackType' => $request->attackType,
                'cite' => $request->cite,
                'citations' => $request->citations,
                'downloadLinks' => $request->downloadLinks,
                'abstract' => $request->abstract,
                'custom_attributes' => $encodedAttributes,
            ]);


            return redirect()->back()->with('success', 'Dataset added successfully.');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Failed to add dataset. Please try again.');
        }
    }

    public function deleteDataset($id){
        $dataset = Dataset::find($id);
        $dataset->delete();
        return redirect()->back()->with('success', 'Dataset deleted successfully');
    }

    public function showEditDataset($id){
        $dataset = Dataset::find($id);
        return view('projects.editDataset', ['dataset' => $dataset]);
    }

    public function updateDataset(Request $request, $id){
        $dataset = Dataset::find($id);

        $validator = Validator::make($request->all(), [
            'serialNumber' => 'required|numeric',
            'year' => 'required|numeric',
            'dataset' => 'required|string|max:255',
            'kindOfTraffic' => 'required|string|max:255',
            'publicallyAvailable' => 'required|in:yes,no',
            'countRecords' => 'required|string|max:255',
            'featuresCount' =>'required|string|max:255',
            'cite' => 'required|string',
            'citations' => 'required|numeric',
            'attackType' => 'required|string|max:255',
            'downloadLinks' => 'required|string|max:255',
            'abstract' => 'required|string',
            'custom_attributes' => 'nullable|array',
        ]);


        $customAttributes = $request->input('custom_attributes', []);
        $encodedAttributes = json_encode($customAttributes);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $dataset->update([
                'serialNumber' => $request->serialNumber,
                'year' => $request->year,
                'dataset' => $request->dataset,
                'kindOfTraffic' => $request->kindOfTraffic,
                'publicallyAvailable' => $request->publicallyAvailable,
                'countRecords' => $request->countRecords,
                'featuresCount' => $request->featuresCount,
                'cite' => $request->cite,
                'citations' => $request->citations,
                'attackType' => $request->attackType,
                'downloadLinks' => $request->downloadLinks,
                'abstract' => $request->abstract,
                'custom_attributes' => $encodedAttributes,
            ]);

            return redirect()->route('project.show', $dataset->project_id)->with('success', 'Dataset updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update dataset. Please try again.');
        }
    }

    public function searchDatasets(Request $request, Project $project)
{

    $maxSerialNumber = Dataset::where('project_id', $project->id)->max('serialNumber');
    $maxSerialNumber = isset($maxSerialNumber) ? $maxSerialNumber + 1 : 0;
    $searchString = $request->input('search');
    $column = $request->input('column');


    if ($column === 'all') {
        $datasets = Dataset::where('project_id', $project->id)
            ->where(function ($query) use ($searchString) {
                $query->where('serialNumber', 'LIKE', "%$searchString%")
                    ->orWhere('dataset', 'LIKE', "%$searchString%")
                    ->orWhere('year', 'LIKE', "%$searchString%")
                    ->orWhere('kindOfTraffic', 'LIKE', "%$searchString%")
                    ->orWhere('publicallyAvailable', 'LIKE', "%$searchString%")
                    ->orWhere('countRecords', 'LIKE', "%$searchString%")
                    ->orWhere('featuresCount', 'LIKE', "%$searchString%")
                    ->orWhere('cite', 'LIKE', "%$searchString%")
                    ->orWhere('attackType', 'LIKE', "%$searchString%")
                    ->orWhere('downloadLinks', 'LIKE', "%$searchString%")
                    ->orWhere('abstract', 'LIKE', "%$searchString%");
            })
            ->get();
    } else {

        $datasets = Dataset::where('project_id', $project->id)
            ->where($column, 'LIKE', "%$searchString%")
            ->get();
    }


    return view('projects.projectShow', compact('project', 'datasets', 'maxSerialNumber'));
}


 public function acceptContribution($id)
 {
     DB::beginTransaction();

     try {

         $contributionRequest = ContributionRequest::findOrFail($id);


         $dataset = new Dataset();
         $dataset->project_id = $contributionRequest->project_id;
         $dataset->serialNumber = $contributionRequest->serialNumber;
         $dataset->year = $contributionRequest->year;
         $dataset->dataset = $contributionRequest->dataset;
         $dataset->kindOfTraffic = $contributionRequest->kindOfTraffic;
         $dataset->publicallyAvailable = $contributionRequest->publicallyAvailable;
         $dataset->countRecords = $contributionRequest->countRecords;
         $dataset->featuresCount = $contributionRequest->featuresCount;
         $dataset->cite = $contributionRequest->cite;
         $dataset->citations = $contributionRequest->citations;
         $dataset->attackType = $contributionRequest->attackType;
         $dataset->downloadLinks = $contributionRequest->downloadLinks;
         $dataset->abstract = $contributionRequest->abstract;
         $dataset->save();


         $contributionRequest->status = 'accepted';
         $contributionRequest->save();


         DB::commit();

         return redirect()->route('manageContributionRequests', ['id' => $contributionRequest->project_id])
                          ->with('success', 'Contribution accepted successfully.');

     } catch (\Exception $e) {

         DB::rollBack();
         return back()->with('error', 'An error occurred while accepting the contribution.');
     }
 }


 public function rejectContribution($id)
 {
     DB::beginTransaction();

     try {

         $contributionRequest = ContributionRequest::findOrFail($id);


         $contributionRequest->status = 'rejected';
         $contributionRequest->save();


         DB::commit();

         return redirect()->route('manageContributionRequests', ['id' => $contributionRequest->project_id])
                          ->with('success', 'Contribution rejected successfully.');

     } catch (\Exception $e) {

         DB::rollBack();
         return back()->with('error', 'An error occurred while rejecting the contribution.');
     }
 }


 public function ignoreContribution($id)
 {
     DB::beginTransaction();

     try {

         $contributionRequest = ContributionRequest::findOrFail($id);

         $contributionRequest->status = 'pending';
         $contributionRequest->save();

         DB::commit();

         return redirect()->route('manageContributionRequests', ['id' => $contributionRequest->project_id])
                          ->with('success', 'Contribution ignored successfully.');

     } catch (\Exception $e) {

         DB::rollBack();
         return back()->with('error', 'An error occurred while ignoring the contribution.');
     }
 }

 public function showDatasetDetails($id)
 {
     $dataset = Dataset::findOrFail($id);

     return view('projects.datasetDetails', compact('dataset'));
 }

 public function uploadExcelFile(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
        'csv_file' => 'required|file|mimes:csv,txt|max:2048',
    ]);

    $file = $request->file('csv_file');
    $projectId = $request->input('project_id');

    $path = $file->getRealPath();
    $rows = array_map('str_getcsv', file($path));
    $header = array_map('trim', array_shift($rows));

    $expected = [
        'serialNumber', 'year', 'dataset', 'kindOfTraffic', 'publicallyAvailable',
        'countRecords', 'featuresCount', 'cite', 'citations', 'attackType',
        'downloadLinks', 'abstract', 'custom_attributes'
    ];

    if ($header !== $expected) {
        return back()->with('error', 'CSV headers do not match the expected format.');
    }

    $maxSerial = Dataset::where('project_id', $projectId)->max('serialNumber') ?? 0;

    $datasets = [];
    foreach ($rows as $index => $row) {
        $data = array_combine($header, $row);

        if (Dataset::where('serialNumber', $data['serialNumber'])->exists()) {
            $maxSerial++;
            $data['serialNumber'] = $maxSerial;
        }

        $datasets[] = [
            'project_id' => $projectId,
            'serialNumber' => (int) $data['serialNumber'],
            'year' => $data['year'],
            'dataset' => $data['dataset'],
            'kindOfTraffic' => $data['kindOfTraffic'],
            'publicallyAvailable' => $data['publicallyAvailable'] ?? null,
            'countRecords' => $data['countRecords'] ?? null,
            'featuresCount' => $data['featuresCount'] ?? null,
            'cite' => $data['cite'],
            'citations' => (int) $data['citations'],
            'attackType' => $data['attackType'] ?? null,
            'downloadLinks' => $data['downloadLinks'] ?? null,
            'abstract' => $data['abstract'],
            'custom_attributes' => $data['custom_attributes'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    Dataset::insert($datasets);

    return back()->with('success', 'CSV data uploaded successfully.');
}

public function downloadSampleCsv()
{
    $columns = Schema::getColumnListing('datasets');

    $excluded = ['id', 'project_id', 'created_at', 'updated_at'];
    $headers = array_diff($columns, $excluded);

    $rows = [];
    for ($i = 1; $i <= 10; $i++) {
        $row = [];
        foreach ($headers as $column) {
            switch ($column) {
                case 'serialNumber':
                    $row[] = $i;
                    break;
                case 'year':
                    $row[] = 2020 + $i;
                    break;
                case 'dataset':
                    $row[] = "Dataset $i";
                    break;
                case 'kindOfTraffic':
                    $row[] = 'Normal';
                    break;
                case 'publicallyAvailable':
                    $row[] = 'yes';
                    break;
                case 'countRecords':
                    $row[] = '10000';
                    break;
                case 'featuresCount':
                    $row[] = '42';
                    break;
                case 'cite':
                    $row[] = "Citation text for dataset $i";
                    break;
                case 'citations':
                    $row[] = rand(100, 500);
                    break;
                case 'attackType':
                    $row[] = 'DoS';
                    break;
                case 'downloadLinks':
                    $row[] = 'http://example.com';
                    break;
                case 'abstract':
                    $row[] = "This is a short abstract for dataset $i.";
                    break;
                case 'custom_attributes':
                    $row[] = '{"label_type":"binary","source":"cic"}';
                    break;
                default:
                    $row[] = '';
            }
        }
        $rows[] = $row;
    }

    return new StreamedResponse(function () use ($headers, $rows) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, $headers);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    }, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="sample_dataset.csv"',
    ]);
}


}
