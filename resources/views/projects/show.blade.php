@extends('layouts.app')
@section('content')
    @include('layouts/header')
    
    <section class="project-detail" id="project-detail">
        <div class="container">
            <div class="project-header">
                <div class="project-header__title">
                    <div class="section-body-page__info wow fadeInLeft blick" data-wow-duration="2s" data-wow-delay="2s">
                        <p>По 02.02.2025 скидка на все 5%</p>
                    </div>
                    <h1 class="wow fadeInDown" data-wow-duration="0.5s" data-wow-delay="0.5s">
                        {{ $project->title }}
                    </h1>
                    <div class="description-content wow fadeInDown" data-wow-duration="0.5s" data-wow-delay="0.5s">
                        {!! nl2br(e($project->description)) !!}
                    </div>
                    <div class="project-meta wow fadeInDown" data-wow-duration="0.8s" data-wow-delay="0.8s">
                        <div class="project-meta-item">
                            <span>Площадь</span>
                            <strong>{{ $project->area }}</strong>
                        </div>
                        <div class="project-meta-item">
                            <span>Сроки</span>
                            <strong>{{ $project->time }}</strong>
                        </div>
                        @if($project->price)
                        <div class="project-meta-item">
                            <span>Стоимость работы</span>
                            <strong>{{ $project->price }} руб</strong>
                        </div>
                        @endif
                    </div>
                    <div class="section-body-page__buttons wow fadeInLeft" data-wow-duration="1.2s" data-wow-delay="1.2s">
                        <button class="case_button"> <img src="{{ asset('img/icon/radio.svg') }}" alt=""> Еще  кейсы</button>
                        <button class="blick" onclick="openQuizModal()">Хочу тоже! <img src="{{ asset('img/icon/comment.svg ') }}"
                                alt=""></button>
                    </div>
                   
                </div>
                  
            <div class="project-main-image wow fadeInDown" data-wow-duration="1s" data-wow-delay="1s">
                <img src="{{ asset($project->image) }}" alt="{{ $project->title }}">
            </div>
            
            </div>
         
         
            
            @if(count($galleryImages) > 0)
            <div class="project-gallery wow fadeInDown" data-wow-duration="1.4s" data-wow-delay="1.4s">
                <h2>Галерея проекта</h2>
                
                <div class="project-gallery-slider">
                    <swiper-container class="projectGallerySwiper" pagination="true" slides-per-view="3" slides-per-group="3"
                    space-between="30" grid-rows="3" grid-fill="row">
                        @foreach($galleryImages as $image)
                        <swiper-slide>
                            <div class="gallery-slide">
                                <img src="{{ asset($image) }}" alt="{{ $project->title }} - фото {{ $loop->iteration }}" 
                                     onclick="openImageModal('{{ asset($image) }}')">
                            </div>
                        </swiper-slide>
                        @endforeach
                    </swiper-container>
                </div>
            </div>
            @endif
            
            <div class="section-body-page__buttons section-body-page__buttons-bottom wow fadeInDown" data-wow-duration="1.6s" data-wow-delay="1.6s">
                <a href="{{ route('projects.index') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i> Вернуться к проектам
                </a>
                <button class="blick" onclick="openQuizModal()">Получить консультацию <img src="{{ asset('img/icon/phone.svg') }}" alt=""></button>
            </div>
        </div>
    </section>
 
    @include('layouts/quiz')
    @include('layouts/footer')
@endsection
