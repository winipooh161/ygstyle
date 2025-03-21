@extends('layouts.adm')

@section('content')
<div class="admin-container">
    <h1 class="admin-title wow fadeInDown">Модерация отзывов</h1>
    
    @if(session('message'))
        <div class="admin-alert-success">
            {{ session('message') }}
        </div>
    @endif
    
    <div class="admin-tabs">
        <ul class="admin-nav-tabs">
            <li class="admin-nav-tab active" data-target="pending">
                Неодобренные отзывы <span class="admin-badge-warning">{{ $feedbacks->where('approved', false)->count() }}</span>
            </li>
            <li class="admin-nav-tab" data-target="approved">
                Одобренные отзывы <span class="admin-badge-success">{{ $feedbacks->where('approved', true)->count() }}</span>
            </li>
        </ul>
    </div>
    
    <div class="admin-card wow fadeInUp">
        <div class="admin-tab-content" id="pending">
            @if($feedbacks->where('approved', false)->count() > 0)
                <div class="feedback-list">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Отзыв</th>
                                <th>Дата</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feedbacks->where('approved', false) as $feedback)
                            <tr>
                                <td>{{ $feedback->id }}</td>
                                <td>{{ $feedback->name }}</td>
                                <td>{{ $feedback->email }}</td>
                                <td class="feedback-text" data-feedback="{{ $feedback->comment }}">
                                    {{ Str::limit($feedback->comment, 50) }}
                                </td>
                                <td data-sort="{{ $feedback->created_at->timestamp }}">
                                    {{ $feedback->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="actions">
                                    <form action="{{ route('feedback.approve', $feedback->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        <button type="submit" class="admin-btn admin-btn--success">Одобрить</button>
                                    </form>
                                    <button class="admin-btn admin-btn--danger delete-btn" 
                                            data-id="{{ $feedback->id }}" 
                                            data-name="{{ $feedback->name }}">Удалить</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="no-feedback">Нет отзывов, ожидающих модерации.</p>
            @endif
        </div>
        
        <div class="admin-tab-content" id="approved" style="display:none;">
            @if($feedbacks->where('approved', true)->count() > 0)
                <div class="feedback-list">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Отзыв</th>
                                <th>Дата</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($feedbacks->where('approved', true) as $feedback)
                            <tr>
                                <td>{{ $feedback->id }}</td>
                                <td>{{ $feedback->name }}</td>
                                <td>{{ $feedback->email }}</td>
                                <td class="feedback-text" data-feedback="{{ $feedback->comment }}">
                                    {{ Str::limit($feedback->comment, 50) }}
                                </td>
                                <td data-sort="{{ $feedback->created_at->timestamp }}">
                                    {{ $feedback->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="actions">
                                    <form action="{{ route('feedback.disapprove', $feedback->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        <button type="submit" class="admin-btn admin-btn--warning">Отозвать одобрение</button>
                                    </form>
                                    <button class="admin-btn admin-btn--danger delete-btn" 
                                            data-id="{{ $feedback->id }}" 
                                            data-name="{{ $feedback->name }}">Удалить</button>
                                </td>
                            </tr>
                        @endforeach    
                        </tbody>
                    </table>
                </div>
            @else
                <p class="no-feedback">Нет одобренных отзывов.</p>
            @endif
        </div>
    </div>
    
    <!-- Модальное окно для просмотра полного отзыва -->
    <div class="custom-modal" id="feedbackModal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title">Полный текст отзыва</h5>
                <span class="custom-modal-close">&times;</span>
            </div>
            <div class="custom-modal-body">
                <p id="fullFeedbackText"></p>
            </div>
            <div class="custom-modal-footer">
                <button class="admin-btn" onclick="hideModal('feedbackModal')">Закрыть</button>
            </div>
        </div>
    </div>
    
    <!-- Модальное окно подтверждения удаления -->
    <div class="custom-modal" id="deleteModal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title">Подтверждение удаления</h5>
                <span class="custom-modal-close">&times;</span>
            </div>
            <div class="custom-modal-body">
                <p>Вы действительно хотите удалить отзыв от <strong id="deleteName"></strong>?</p>
            </div>
            <div class="custom-modal-footer">
                <button class="admin-btn" onclick="hideModal('deleteModal')">Отмена</button>
                <form id="deleteForm" method="POST" style="display: inline-block;">
                    @csrf @method('DELETE')
                    <button type="submit" class="admin-btn admin-btn--danger">Удалить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Простой переключатель вкладок
    document.querySelectorAll('.admin-nav-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.admin-nav-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const target = this.getAttribute('data-target');
            document.querySelectorAll('.admin-tab-content').forEach(content => {
                content.style.display = content.id === target ? 'block' : 'none';
            });
        });
    });
    
    // Открытие модального окна полного отзыва при клике по тексту
    document.querySelectorAll('.feedback-text').forEach(el => {
        el.style.cursor = 'pointer';
        el.addEventListener('click', function() {
            const feedback = this.getAttribute('data-feedback');
            document.getElementById('fullFeedbackText').innerText = feedback;
            showModal('feedbackModal');
        });
    });
    
    // Подтверждение удаления через модальное окно
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            document.getElementById('deleteName').textContent = name;
            document.getElementById('deleteForm').action = "{{ route('feedback.index') }}/" + id;
            
            showModal('deleteModal');
        });
    });
</script>
@endsection
