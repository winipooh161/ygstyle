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
        $meta_title       = 'ЮГСТИЛЬ - Проекты компании';
        $meta_description = 'Список всех проектов компании';
        $meta_keywords    = 'проекты, ремонт, дизайн';
        $meta_author      = 'Название сайта';
        return view('projects.index', compact('projects', 'meta_title', 'meta_description', 'meta_keywords', 'meta_author'));
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        $galleryImages = $project->getGalleryImages();
        $meta_title       = 'Проект: ' . $project->title;
        $meta_description = $project->description ?: 'Описание проекта';
        $meta_keywords    = 'проект, ремонт, дизайн';
        $meta_author      = 'Название сайта';
        return view('projects.show', compact('project', 'galleryImages', 'meta_title', 'meta_description', 'meta_keywords', 'meta_author'));
    }

    public function gallery($id)
    {
        $project = Project::findOrFail($id);
        $images = $project->getGalleryImages();
        return response()->json($images);
    }
}
