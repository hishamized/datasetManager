<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Dataset;

use Illuminate\Http\Request;

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
}
