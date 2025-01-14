<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'featuresCount' => 'required|numeric',
            'citations' => 'required|numeric',
            'doi' => 'required|string|max:255',
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
                'doi' => $request->doi,
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
            'featuresCount' => 'required|numeric',
            'citations' => 'required|numeric',
            'doi' => 'required|string|max:255',
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
                'citations' => $request->citations,
                'doi' => $request->doi,
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
                    ->orWhere('doi', 'LIKE', "%$searchString%")
                    ->orWhere('downloadLinks', 'LIKE', "%$searchString%")
                    ->orWhere('abstract', 'LIKE', "%$searchString%");
            })
            ->get();
    } else {

        $datasets = Dataset::where('project_id', $project->id)
            ->where($column, 'LIKE', "%$searchString%")
            ->get();
    }


    return view('projects.projectShow', compact('project', 'datasets'));
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
         $dataset->doi = $contributionRequest->doi;
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

}
