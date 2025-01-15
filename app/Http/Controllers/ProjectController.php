<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Project;
use App\Models\Dataset;

class ProjectController extends Controller
{

    public function showCreateForm()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'students' => 'required|string',
            'guide_name' => 'required|string',
        ]);

        $project = new Project();
        $project->title = $request->title;
        $project->description = $request->description;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->students = $request->students;
        $project->guide_name = $request->guide_name;
        $project->user_id = Auth::id();
        $project->save();

        return redirect()->route('dashboard')->with('success', 'Project created successfully');
    }

    public function searchProjects(Request $request){
        $validatedData = $request->validate([
            'search' => 'nullable|string',
            'column' => 'nullable|string',
        ]);

        $searchQuery = $validatedData['search'];
        $column = $validatedData['column'];

        if (empty($searchQuery)) {
            $projects = Project::all();
        } else {
            $projects = Project::when($column !== 'all', function ($query) use ($column, $searchQuery) {
                return $query->where($column, 'like', '%' . $searchQuery . '%');
            }, function ($query) use ($searchQuery) {
                return $query->where(function ($query) use ($searchQuery) {
                    $query->where('title', 'like', '%' . $searchQuery . '%')
                        ->orWhere('description', 'like', '%' . $searchQuery . '%')
                        ->orWhere('start_date', 'like', '%' . $searchQuery . '%')
                        ->orWhere('end_date', 'like', '%' . $searchQuery . '%')
                        ->orWhere('students', 'like', '%' . $searchQuery . '%')
                        ->orWhere('guide_name', 'like', '%' . $searchQuery . '%');
                });
            })->get();
        }

        return view('projects.projectsList', compact('projects'));
    }

    public function viewProjects(){
        $projects = Project::where('user_id', Auth::id())->get();
        return view('projects.projectsList', ['projects' => $projects]);
    }

    public function showProject($id)
    {
        $maxSerialNumber = Dataset::where('project_id', $id)->max('serialNumber');
        $maxSerialNumber = isset($maxSerialNumber) ? $maxSerialNumber + 1 : 0;

        $project = Project::with('datasets')->find($id);


        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Project not found.');
        }


        return view('projects.projectShow', [
            'project' => $project,
            'datasets' => $project->datasets,
            'maxSerialNumber' => $maxSerialNumber
        ]);
    }


    public function destroyProject($id){
        $project = Project::find($id);
        $project->delete();
        return redirect()->route('projects.view')->with('success', 'Project deleted successfully');
    }

    public function editProjectPage($id){
        $project = Project::find($id);
        return view('projects.editProject', ['project' => $project]);
    }

    public function updateProject(Request $request, $id){
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'students' => 'required|string',
            'guide_name' => 'required|string',
        ]);

        $project = Project::find($id);
        $project->title = $request->title;
        $project->description = $request->description;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->students = $request->students;
        $project->guide_name = $request->guide_name;
        $project->save();

        return redirect()->route('projects.view')->with('success', 'Project updated successfully');
    }

    public function manageContributionRequests($id){
        $project = Project::find($id);
        return view('projects.manageContributionRequests', ['project' => $project]);
    }


}
