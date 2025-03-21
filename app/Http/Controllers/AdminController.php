<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Feedback;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Можно добавить статистику для дашборда
        $usersCount = User::count();
        $projectsCount = Project::count();
        
        // Если есть модель Feedback для отзывов
        $feedbackCount = class_exists('App\Models\Feedback') ? Feedback::count() : 0;

        return view('admin.dashboard', compact('usersCount', 'projectsCount', 'feedbackCount'));
    }

    /**
     * Show the projects management page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function projects()
    {
        $projects = Project::orderBy('id', 'desc')->get();
        return view('admin.projects', compact('projects'));
    }

    public function createProject(Request $request)
    {
        // Проверяем, если есть параметр edit, значит это редактирование
        if ($request->has('edit')) {
            $project = Project::findOrFail($request->edit);
            return view('admin.projects.create', compact('project'));
        }
        
        // Если нет параметра edit, значит это создание нового проекта
        return view('admin.projects.create');
    }

    public function storeProject(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'area' => 'nullable|string|max:100',
            'time' => 'nullable|string|max:100',
            'price' => 'nullable|string|max:100',
            'content' => 'nullable|string',
          
        ]);

        // Генерация slug, если не указан
        $slug = $request->slug ? $request->slug : Str::slug($request->title);
        
        // Обработка загрузки основного изображения
        $imagePath = $request->file('image')->store('projects/images', 'public');
        
        // Создание проекта
        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => 'storage/' . $imagePath,
            'area' => $request->area,
            'time' => $request->time,
            'price' => $request->price,
            'content' => $request->content,
           
        ]);
        
        // Обработка загрузки галереи
        if ($request->hasFile('gallery')) {
            // Создаем директорию для галереи проекта
            $galleryPath = 'public/gallery/' . $project->id;
            if (!Storage::exists($galleryPath)) {
                Storage::makeDirectory($galleryPath);
            }
            
            foreach ($request->file('gallery') as $image) {
                // Сохраняем изображение в папку галереи проекта
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs($galleryPath, $image, $filename);
            }
        }
        
        return redirect()->route('admin.projects')->with('success', 'Проект успешно создан');
    }

    public function updateProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'area' => 'nullable|string|max:100',
            'time' => 'nullable|string|max:100',
            'price' => 'nullable|string|max:100',
            'content' => 'nullable|string',
           
        ]);
        
        // Генерация slug, если не указан
        $slug = $request->slug ? $request->slug : Str::slug($request->title);
        
        // Обработка загрузки изображения
        $imagePath = $project->image;
        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($project->image && file_exists(public_path($project->image))) {
                unlink(public_path($project->image));
            }
            
            $imagePath = 'storage/' . $request->file('image')->store('projects/images', 'public');
        }
        
        // Обновление проекта
        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'area' => $request->area,
            'time' => $request->time,
            'price' => $request->price,
            'content' => $request->content,
            
        ]);
        
        // Обработка галереи
        if ($request->hasFile('gallery')) {
            // Создаем директорию для галереи проекта, если не существует
            $galleryPath = 'public/gallery/' . $project->id;
            if (!Storage::exists($galleryPath)) {
                Storage::makeDirectory($galleryPath);
            }
            
            foreach ($request->file('gallery') as $image) {
                // Сохраняем новое изображение
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs($galleryPath, $image, $filename);
            }
        }
        
        // Обработка удаления изображений из галереи
        $existingFiles = $request->input('existing_gallery', []);
        $galleryPath = 'public/gallery/' . $project->id;
        
        if (Storage::exists($galleryPath)) {
            $files = Storage::files($galleryPath);
            
            foreach ($files as $file) {
                $filename = basename($file);
                if (!in_array($filename, $existingFiles)) {
                    Storage::delete($file);
                }
            }
        }
        
        return redirect()->route('admin.projects')->with('success', 'Проект успешно обновлен');
    }
}
