<section class="gallery" id="gallery">

    <div class="title">
        <h2 class="wow fadeInDown" data-wow-duration="0.5s" data-wow-delay="0.5s"> ГАЛЕРЕЯ <span class="color-text">НАШИХ</span> РАБОТ </h2>
        <p class="wow fadeInDown" data-wow-duration="1.2s" data-wow-delay="1.2s"> Посмотрите примеры реализованных нами проектов и <strong>убедитесь в качестве</strong> выполненных работ</p>
    </div>
    
    @if(isset($projectImages) && count($projectImages) > 0)
        @php
            // Сортируем проекты по убыванию ключей (идентификаторов)
            $projectImages = collect($projectImages)->sortKeysDesc()->toArray();
        @endphp
        @foreach($projectImages as $projectId => $images)
        <div class="gallery__slider wow fadeInDown" data-wow-duration="0.5s" data-wow-delay="0.5s">
            <!-- Добавляем название проекта, если оно есть и нужно показывать заголовки -->
            @if(isset($allProjects[$projectId]) && (!isset($showProjectTitles) || $showProjectTitles))
                <div class="gallery-project-title">
                    <h3>{{ $allProjects[$projectId]->title }}</h3>
                </div>
            @endif
            
            <swiper-container class="gallery-swiper-{{ $loop->index + 1 }} @if($loop->index % 2 == 1) reverse @endif"
                slides-per-view="3" slides-per-group="1"
                space-between="30" grid-rows="1" grid-fill="row"
                autoplay-delay="{{ 3000 + ($loop->index * 1000) }}" autoplay="true"
                breakpoints='{"320": {"slidesPerView": 1}, "640": {"slidesPerView": 2}, "1024": {"slidesPerView": 3}}'>
                @foreach($images as $i => $img)
                <swiper-slide>
                    <div class="gallery-item">
                        @if(isset($allProjects[$projectId]))
                            <a href="{{ route('projects.show', $projectId) }}">
                                <img src="{{ asset($img) }}" alt="Фото работы {{ $allProjects[$projectId]->title }}" loading="lazy" onload="this.classList.add('loaded')">
                            </a>
                        @else
                            <img src="{{ asset($img) }}" alt="Фото работы" loading="lazy" onload="this.classList.add('loaded')" onclick="openModal('{{ $projectId }}', {{ $i }})">
                        @endif
                    </div>
                </swiper-slide>
                @endforeach
            </swiper-container>
        </div>
        @endforeach
    @else
        <!-- Используем старую структуру, если новой нет -->
        @foreach($chunkedImages as $chunkIndex => $slider)
        <div class="gallery__slider wow fadeInDown" data-wow-duration="0.5s" data-wow-delay="0.5s">
            <swiper-container class="gallery-swiper-{{ $chunkIndex + 1 }} @if($chunkIndex % 2 == 1) reverse @endif"
                slides-per-view="3" slides-per-group="1"
                space-between="30" grid-rows="1" grid-fill="row"
                autoplay-delay="{{ 3000 + ($chunkIndex * 1000) }}" autoplay="true"
                breakpoints='{"320": {"slidesPerView": 1}, "640": {"slidesPerView": 2}, "1024": {"slidesPerView": 3}}'>
                @foreach($slider as $i => $img)
                <swiper-slide>
                    <div class="gallery-item">
                        <img src="{{ asset($img) }}" alt="Фото работы" loading="lazy" onload="this.classList.add('loaded')" onclick="openModal({{ $chunkIndex }}, {{ $i }})">
                    </div>
                </swiper-slide>
                @endforeach
            </swiper-container>
        </div>
        @endforeach
    @endif
</section>

<!-- Модальное окно для проектов без привязки к базе данных -->
<div id="imageModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <swiper-container id="modalSwiper" slides-per-view="1" space-between="30" style="width:100%; height:100%;">
            <!-- Слайды будут заполнены динамически -->
        </swiper-container>
    </div>
</div>

<script>
// Глобальная переменная для хранения изображений проектов
var projectImages = @json(isset($projectImages) ? $projectImages : (isset($chunkedImages) ? $chunkedImages : []));

// Функция открытия модального окна с слайдером
function openModal(projectId, slideIndex) {
    var images = projectImages[projectId];
    var modalSwiper = document.getElementById('modalSwiper');
    var slidesHtml = '';
    
    images.forEach(function(img) {
        slidesHtml += '<swiper-slide><img src="'+img+'" alt="Модальное изображение" style=""></swiper-slide>';
    });
    
    modalSwiper.innerHTML = slidesHtml;
    document.getElementById('imageModal').style.display = 'flex';
    
    // Дождаться апгрейда элемента и перейти к нужному слайду
    setTimeout(function(){
        if(modalSwiper.swiper) {
            modalSwiper.swiper.slideTo(slideIndex, 0);
        }
    }, 100);
}

// Функция закрытия модального окна
function closeModal() {
    document.getElementById('imageModal').style.display = 'none';
    document.getElementById('modalSwiper').innerHTML = ''; // очистка слайдов
}

// Закрытие модального окна по клику вне его содержимого
document.getElementById('imageModal').addEventListener('click', function(e) {
    if(e.target.id === 'imageModal'){
        closeModal();
    }
});
</script>
