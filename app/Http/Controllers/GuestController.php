<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Dataset;

use Illuminate\Http\Request;
use App\Models\ContributionRequest;

class GuestController extends Controller
{
    public function showProjectsPublicly()
    {
        $projects = Project::all();
        return view('projects.showProjectsPublicly', ['projects' => $projects]);
    }

    public function showProjectPublicly($id)
    {
        $project = Project::find($id);
        $datasets = $project->datasets;
        return view('projects.viewProjectPublicly', ['project' => $project, 'datasets' => $datasets]);
    }

    public function searchDatasetsPublic(Request $request, $id)
    {

        $searchQuery = $request->input('search');
        $column = $request->input('column');


        $project = Project::findOrFail($id);


        if (empty($searchQuery)) {
            $datasets = Dataset::where('project_id', $id)->get();
        } else {

            $datasets = Dataset::where('project_id', $id)
                ->when($column !== 'all', function ($query) use ($column, $searchQuery) {
                    return $query->where($column, 'like', '%' . $searchQuery . '%');
                }, function ($query) use ($searchQuery) {

                    return $query->where(function ($query) use ($searchQuery) {
                        $query->where('serialNumber', 'like', '%' . $searchQuery . '%')
                            ->orWhere('dataset', 'like', '%' . $searchQuery . '%')
                            ->orWhere('year', 'like', '%' . $searchQuery . '%')
                            ->orWhere('kindOfTraffic', 'like', '%' . $searchQuery . '%')
                            ->orWhere('publicallyAvailable', 'like', '%' . $searchQuery . '%')
                            ->orWhere('countRecords', 'like', '%' . $searchQuery . '%')
                            ->orWhere('featuresCount', 'like', '%' . $searchQuery . '%')
                            ->orWhere('doi', 'like', '%' . $searchQuery . '%')
                            ->orWhere('downloadLinks', 'like', '%' . $searchQuery . '%')
                            ->orWhere('abstract', 'like', '%' . $searchQuery . '%');
                    });
                })
                ->get();
        }


        return view('projects.viewProjectPublicly', compact('project', 'datasets'));
    }

    public function makeContributionRequest($id)
    {
        $project = Project::findOrFail($id);
        $maxSerialNumber = Dataset::where('project_id', $id)->max('serialNumber');
        $maxSerialNumber = $maxSerialNumber ? $maxSerialNumber + 1 : 0;
        return view('projects.contributionRequests', ['project' => $project, 'maxSerialNumber' => $maxSerialNumber]);
    }

    public function submitContributionRequest(Request $request)
    {

        $existingRequest = ContributionRequest::where('project_id', $request->input('project_id'))
            ->where('status', 'pending')
            ->where('email', $request->input('email'))
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'A pending contribution request already exists for this project.');
        }


        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:15',
            'project_id' => 'required|exists:projects,id',
            'serialNumber' => 'required|integer',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'dataset' => 'required|string|max:255',
            'kindOfTraffic' => 'required|string|max:255',
            'publicallyAvailable' => 'required|in:yes,no',
            'countRecords' => 'required|string|max:255',
            'featuresCount' => 'required|integer',
            'doi' => 'nullable|string|max:255',
            'downloadLinks' => 'nullable|string',
            'abstract' => 'required|string',
        ]);


        $existingDataset = Dataset::where('project_id', $validatedData['project_id'])
            ->where('serialNumber', $validatedData['serialNumber'])
            ->first();


        if ($existingDataset) {
            $highestSerialNumber = Dataset::where('project_id', $validatedData['project_id'])->max('serialNumber');
            $validatedData['serialNumber'] = $highestSerialNumber + 1;
        }

        try {

            ContributionRequest::create([
                'full_name' => $validatedData['full_name'],
                'email' => $validatedData['email'],
                'phone_number' => $validatedData['phone_number'],
                'project_id' => $validatedData['project_id'],
                'serialNumber' => $validatedData['serialNumber'],
                'year' => $validatedData['year'],
                'dataset' => $validatedData['dataset'],
                'kindOfTraffic' => $validatedData['kindOfTraffic'],
                'publicallyAvailable' => $validatedData['publicallyAvailable'],
                'countRecords' => $validatedData['countRecords'],
                'featuresCount' => $validatedData['featuresCount'],
                'doi' => $validatedData['doi'],
                'downloadLinks' => $validatedData['downloadLinks'],
                'abstract' => $validatedData['abstract'],
                'status' => 'pending',
            ]);

        } catch (\Exception $e) {


            return redirect()->back()->with('error', $e->getMessage());
        }


        return redirect()->route('project.show.publicly', ['id' => $validatedData['project_id']])
            ->with('success', 'Your contribution request has been submitted successfully.');
    }

    public function showDatasetDetailsPublicly($id)
    {
        $dataset = Dataset::find($id);
        return view('projects.datasetDetails', ['dataset' => $dataset]);
    }

}
