@extends('layouts.adm')

@section('content')
<div class="admin-container">
    <h1 class="admin-title wow fadeInDown">{{ isset($project) ? 'Редактирование проекта' : 'Создание проекта' }}</h1>
    @if(session('success'))
        <div class="admin-alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="admin-alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="admin-card wow fadeInUp">
        <div class="header-section flex-between">
            <h5>{{ isset($project) ? 'Редактирование проекта: ' . $project->title : 'Новый проект' }}</h5>
            <a href="{{ route('admin.projects') }}" class="admin-btn admin-btn--link">
                <i class="fas fa-arrow-left"></i> Вернуться к списку
            </a>
        </div>
        <form action="{{ isset($project) ? route('admin.projects.update', $project->id) : route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" id="projectForm">
            @csrf
            @if(isset($project))
                @method('PUT')
            @endif

            <!-- Основные данные проекта -->
            <div class="custom-row">
                <div class="custom-col-8">
                    <div class="admin-form-group">
                        <label for="title" class="admin-label">Название проекта <span class="custom-required">*</span></label>
                        <input type="text" id="title" name="title" class="admin-input" value="{{ old('title', $project->title ?? '') }}" required>
                    </div>
                    <div class="admin-form-group">
                        <label for="description" class="admin-label">Краткое описание <span class="custom-required">*</span></label>
                        <textarea id="description" name="description" rows="3" class="admin-textarea" required>{{ old('description', $project->description ?? '') }}</textarea>
                    </div>
                    <div class="row-form-group">
                        <div class="col-md-4 admin-form-group">
                            <label for="area" class="admin-label">Площадь объекта</label>
                            <input type="text" id="area" name="area" class="admin-input" value="{{ old('area', $project->area ?? '') }}">
                        </div>
                        <div class="col-md-5 admin-form-group">
                            <label for="time" class="admin-label">Сроки выполнения</label>
                            <input type="text" id="time" name="time" class="admin-input" value="{{ old('time', $project->time ?? '') }}">
                        </div>
                        <div class="col-md-4 admin-form-group">
                            <label for="price" class="admin-label">Стоимость</label>
                            <input type="text" id="price" name="price" class="admin-input" value="{{ old('price', $project->price ?? '') }}">
                        </div>
                    </div>
                    <div class="admin-form-group">
                        <label for="content" class="admin-label">Полное описание</label>
                        <textarea id="content" name="content" rows="10" class="admin-textarea">{{ old('content', $project->content ?? '') }}</textarea>
                    </div>
                </div>
                <div class="custom-col-4">
                    <div class="admin-form-group">
                        <label for="image" class="admin-label">Основное изображение <span class="custom-required">*</span></label>
                        <input type="file" id="image" name="image" accept="image/*" class="custom-input-file" {{ isset($project) ? '' : 'required' }}>
                        @if(isset($project) && $project->image)
                            <div class="custom-image-current">
                                <p class="custom-text-muted">Текущее изображение:</p>
                                <div class="custom-image-wrapper">
                                    <img src="{{ asset($project->image) }}" alt="{{ $project->title }}">
                                </div>
                            </div>
                        @endif
                    </div>
                    <div id="imagePreviewContainer" class="custom-image-preview" style="display:none;">
                        <p class="custom-text-muted">Предпросмотр:</p>
                        <img id="imagePreview" src="#" alt="Предпросмотр">
                    </div>
                </div>
            </div>
            <div class="custom-col-12">
                <div class="admin-form-group">
                    <h5>Галерея изображений</h5>
                    <p class="custom-text-muted">Загрузите дополнительные изображения для проекта</p>
                    
                    <label for="gallery" class="admin-label">Добавить изображения в галерею</label>
                    <input type="file" id="gallery" name="gallery[]" accept="image/*" multiple class="custom-input-file">
                    
                    <div id="galleryPreviewContainer" class="custom-gallery-preview row"></div>
                    
                    @if(isset($project) && !empty($galleryImages = $project->getGalleryImages()))
                        <div class="custom-existing-gallery">
                            <h6>Существующие изображения</h6>
                            <div class="row">
                                @foreach($galleryImages as $image)
                                    <div class="col-md-3 custom-gallery-item">
                                        <div class="custom-image-wrapper">
                                            <img src="{{ asset($image) }}" alt="Gallery image">
                                            <button type="button" class="admin-btn admin-btn--danger custom-delete-gallery" data-image="{{ basename($image) }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <input type="hidden" name="existing_gallery[]" value="{{ basename($image) }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex-end" style="gap:15px; margin-top:20px;">
                <button type="button" class="admin-btn admin-btn--link" onclick="window.location.href='{{ route('admin.projects') }}'">Отмена</button>
                <button type="submit" class="admin-btn">
                    <i class="fas fa-save"></i> {{ isset($project) ? 'Обновить проект' : 'Создать проект' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
<script>
$(document).ready(function() {
    // Функция транслитерации для генерации slug
    function slugify(text) {
        const from = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
        const to = 'abvgdeejzijklmnoprstufhzcssyye';
        text = text.toLowerCase().trim();
        let result = '';
        for (let i = 0; i < text.length; i++) {
            const char = text[i];
            const index = from.indexOf(char);
            result += index >= 0 ? to[index] : char;
        }
        return result
            .replace(/[^a-z0-9-]/g, '-') // Заменяем не-алфавитные и не-цифровые символы на дефис
            .replace(/-+/g, '-')         // Заменяем множественные дефисы на одиночный
            .replace(/^-|-$/g, '');      // Удаляем дефисы в начале и конце
    }
    // Генерация slug из заголовка
    $('#generateSlug').click(function() {
        const titleValue = $('#title').val();
        if (titleValue) {
            $('#slug').val(slugify(titleValue));
        } else {
            alert('Сначала введите название проекта');
        }
    });
    // Предпросмотр основного изображения
    $('#image').change(function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
                $('#imagePreviewContainer').show(); // заменено removeClass('d-none') на show()
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
    // Предпросмотр изображений галереи
    $('#gallery').change(function() {
        $('#galleryPreviewContainer').empty();
        
        if (this.files) {
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const previewCol = $('<div class="col-md-3 custom-gallery-item"></div>');
                    const previewImg = $('<img class="img-thumbnail" style="width:100%; height:150px; object-fit:cover;">');
                    
                    previewImg.attr('src', e.target.result);
                    previewCol.append(previewImg);
                    $('#galleryPreviewContainer').append(previewCol);
                }
                
                reader.readAsDataURL(file);
            }
        }
    });
    // Удаление существующих изображений галереи
    $('.delete-gallery-image').click(function() {
        $(this).closest('.gallery-item').remove();
    });
    // Проверка формы перед отправкой
    $('#projectForm').submit(function(e) {
        // Получение содержимого из TinyMCE
        if (tinymce.get('content')) {
            const contentValue = tinymce.get('content').getContent();
            $('#content').val(contentValue);
        }
    });
    // Маска для поля "Площадь объекта" - добавляет суффикс " м²" при потере фокуса, если его нет
    $('#area').on('blur', function() {
        let val = $(this).val().trim();
        if(val && !val.endsWith(' м²')) {
            $(this).val(val + ' м²');
        }
    });
    // Маска для поля "Стоимость" - добавляет префикс "р " при потере фокуса, если его нет
    $('#price').on('blur', function() {
        let val = $(this).val().trim();
        if(val && !val.startsWith('р ')) {
            $(this).val('р ' + val);
        }
    });
    // Строгая маска для поля "Площадь объекта": разрешаем только цифры и точку, затем добавляем суффикс " м²"
    $('#area').on('input', function() {
        let numeric = $(this).val().replace(/[^\d\.]/g, "");
        $(this).val(numeric + " м²");
    });
    
    // Строгая маска для поля "Стоимость": разрешаем только цифры, затем добавляем префикс "р " в начале
    $('#price').on('input', function() {
        let numeric = $(this).val().replace(/[^\d]/g, "");
        $(this).val("р " + numeric);
    });
});

document.addEventListener("DOMContentLoaded", function() {
    var areaField = document.getElementById("area");
    if (areaField) {
        areaField.addEventListener("blur", function() {
            var val = this.value.trim();
            if (val && !val.endsWith(" м²")) {
                this.value = val + " м²";
            }
        });
    }
    
    var priceField = document.getElementById("price");
    if (priceField) {
        priceField.addEventListener("blur", function() {
            var val = this.value.trim();
            if (val && !val.startsWith("р ")) {
                this.value = "р " + val;
            }
        });
    }
});
</script>


