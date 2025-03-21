<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'area',
        'time',
        'content',
        'price',
        'slug'
    ];

    /**
     * Получить все изображения галереи для проекта
     *
     * @return array
     */
    public function getGalleryImages()
    {
        $path = 'public/gallery/' . $this->id;
        
        if (!Storage::exists($path)) {
            return [];
        }
        
        $files = Storage::files($path);
        
        return array_map(function($file) {
            return str_replace('public', 'storage', $file);
        }, $files);
    }
}
