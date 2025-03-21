<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;

class GalleryController extends Controller
{
    public function index()
    {
        // Получаем все директории в папке gallery
        $directories = Storage::directories('public/gallery');
        
        // Создаем массив для хранения изображений по проектам
        $projectImages = [];
        
        // Для каждой директории получаем файлы и формируем массив путей
        foreach ($directories as $directory) {
            $dirName = basename($directory);
            $files = Storage::files($directory);
            
            // Пропускаем пустые директории
            if (empty($files)) continue;
            
            // Формируем массив путей с заменой public на storage для правильного отображения
            $images = array_map(function($file) {
                return str_replace('public', 'storage', $file);
            }, $files);
            
            // Сохраняем изображения под именем директории (ID проекта)
            $projectImages[$dirName] = $images;
        }
        
        // Для страницы галереи можно использовать до 10 проектов
        if (count($projectImages) > 10) {
            $projectImages = array_slice($projectImages, 0, 10, true);
        }
        
        // Если нет папок с проектами, проверяем наличие изображений непосредственно в gallery
        if (empty($projectImages)) {
            $files = Storage::files('public/gallery');
            if (!empty($files)) {
                $images = array_map(function($file) {
                    return str_replace('public', 'storage', $file);
                }, $files);
                
                // Для страницы галереи разбиваем на 10 слайдов
                $sliderCount = 10;
                $chunkSize = ceil(count($images) / $sliderCount);
                $chunkedImages = array_chunk($images, $chunkSize);
                
                // Ограничиваем количество слайдов до 10
                $chunkedImages = array_slice($chunkedImages, 0, 10);
                
                $projectImages['default'] = $images;
            } else {
                $chunkedImages = [];
            }
        } else {
            // Для совместимости создаем chunkedImages из всех изображений проектов
            $allImages = [];
            foreach ($projectImages as $images) {
                $allImages = array_merge($allImages, $images);
            }
            
            $sliderCount = 10;
            $chunkSize = ceil(count($allImages) / $sliderCount);
            $chunkedImages = array_chunk($allImages, $chunkSize);
            
            // Ограничиваем количество слайдов до 10
            $chunkedImages = array_slice($chunkedImages, 0, 10);
        }
        
        // Получаем все проекты для связывания с директориями
        $projects = Project::all()->keyBy('id');
        
        // Для страницы галереи всегда показываем заголовки проектов
        $showProjectTitles = true;
        
        // Для совместимости передаем allProjects
        $allProjects = $projects;
        
        // Убедитесь, что $projectImages передается как массив с ключами-идентификаторами проектов
        return view('gallery', compact('projectImages', 'allProjects', 'showProjectTitles'));
    }
}
