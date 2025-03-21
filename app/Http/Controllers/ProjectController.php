<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        $galleryImages = $project->getGalleryImages();
        
        return view('projects.show', compact('project', 'galleryImages'));
    }

    public function gallery($id)
    {
        $project = Project::findOrFail($id);
        $images = $project->getGalleryImages();
        return response()->json($images);
    }
}
