<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Эксклюзивный ремонт квартиры',
                'image' => 'img/info-cards/cards-1.png',
                'area' => '57 м²',
                'time' => '63 дня',
                'description' => 'Эксклюзивный ремонт однокомнатной квартиры в современном стиле. Проект включал полную перепланировку помещения, замену всех коммуникаций, установку нового сантехнического оборудования и мебели.',
            ],
            [
                'title' => 'Премиум ремонт двухкомнатной квартиры',
                'image' => 'img/info-cards/cards-2.png',
                'area' => '84 м²',
                'time' => '75 дней',
                'description' => 'Премиальный ремонт двухкомнатной квартиры с использованием высококачественных материалов. Проект включал дизайнерские решения, установку умного дома и создание уникального интерьера.',
            ],
            [
                'title' => 'Современный интерьер',
                'image' => 'img/info-cards/cards-3.png',
                'area' => '92 м²',
                'time' => '81 день',
                'description' => 'Создание современного интерьера в трехкомнатной квартире. Минималистичный дизайн с акцентом на функциональность и комфорт, использование натуральных материалов.',
            ],
            [
                'title' => 'Классический ремонт',
                'image' => 'img/info-cards/cards-4.png',
                'area' => '63 м²',
                'time' => '58 дней',
                'description' => 'Классический ремонт двухкомнатной квартиры с элементами неоклассики. Светлые тона, изящная лепнина, качественная отделка и мебель.',
            ],
            [
                'title' => 'Дизайнерский ремонт',
                'image' => 'img/info-cards/cards-1.png',
                'area' => '105 м²',
                'time' => '90 дней',
                'description' => 'Дизайнерский ремонт просторной квартиры в стиле лофт. Открытое пространство, кирпичные стены, промышленные элементы и современная мебель.',
            ],
            [
                'title' => 'Минималистичный стиль',
                'image' => 'img/info-cards/cards-2.png',
                'area' => '78 м²',
                'time' => '72 дня',
                'description' => 'Минималистичный ремонт квартиры с открытой планировкой. Чистые линии, светлые тона, функциональная мебель и максимум свободного пространства.',
            ],
        ];

        // Создаем тестовые проекты
        foreach ($projects as $projectData) {
            $project = Project::create($projectData);
            
            // Создаем директорию для галереи проекта
            $path = 'public/gallery/' . $project->id;
            if (!Storage::exists($path)) {
                Storage::makeDirectory($path);
            }
            
            // Копируем тестовые изображения в галерею проекта
            $sampleImages = [
                'img/info-cards/cards-1.png',
                'img/info-cards/cards-2.png',
                'img/info-cards/cards-3.png',
                'img/info-cards/cards-4.png',
            ];
            
            // Копируем изображения из публичной директории в storage
            foreach ($sampleImages as $index => $imagePath) {
                // Проверяем, существует ли файл в публичной директории
                $publicPath = public_path($imagePath);
                if (File::exists($publicPath)) {
                    // Копируем файл в хранилище с новым именем
                    $newFileName = 'image_' . ($index + 1) . '.png';
                    $storagePath = storage_path('app/' . $path . '/' . $newFileName);
                    
                    // Создаем директорию, если её нет
                    File::ensureDirectoryExists(dirname($storagePath));
                    
                    // Копируем файл
                    File::copy($publicPath, $storagePath);
                }
            }
        }
    }
}
