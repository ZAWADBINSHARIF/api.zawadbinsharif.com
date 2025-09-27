<?php

namespace App\Http\Controllers;

use App\Models\Project as ProjectModel;

class Project extends Controller
{
    public function index()
    {
        $projects = ProjectModel::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'projects' => $projects->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'image' => $project->image ? asset('storage/' . $project->image) : null,
                    'introduction' => $project->introduction,
                    'technology' => $project->technology,
                    'github_link' => $project->github_link,
                    'view_link' => $project->view_link,
                ];
            })
        ]);
    }
}
