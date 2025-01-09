<?php

namespace App\Http\Controllers;
use App\Models\Project;

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
}
