@extends('layouts.admin')

@section('content')
<div class="admin-container">
    <h1 class="admin-title">Управление сообщениями</h1>

    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="admin-card">
        <div class="card-header">
            <h2>Список сообщений</h2>
        </div>
        <div class="card-body">
            @if(isset($messages) && count($messages) > 0)
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Телефон</th>
                            <th>Сообщение</th>
                            <th>Дата</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $message)
                            <tr>
                                <td>{{ $message->id }}</td>
                                <td>{{ $message->name }}</td>
                                <td>{{ $message->email }}</td>
                                <td>{{ $message->phone ?? 'Не указан' }}</td>
                                <td class="message-text">{{ Str::limit($message->message, 50) }}</td>
                                <td>{{ $message->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" 
                                            data-id="{{ $message->id }}" 
                                            data-message="{{ $message->message }}"
                                            onclick="viewMessage(this)">
                                            Просмотр
                                        </button>
                                        <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить это сообщение?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Сообщений пока нет.</p>
            @endif
        </div>
    </div>
</div>

<!-- Модальное окно для просмотра сообщения -->
<div id="messageModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Просмотр сообщения</h5>
            <span class="close" onclick="closeMessageModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div id="fullMessage"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeMessageModal()">Закрыть</button>
        </div>
    </div>
</div>

<script>
    function viewMessage(element) {
        const message = element.getAttribute('data-message');
        document.getElementById('fullMessage').textContent = message;
        document.getElementById('messageModal').style.display = 'flex';
    }

    function closeMessageModal() {
        document.getElementById('messageModal').style.display = 'none';
    }

    // Закрытие модального окна при клике вне содержимого
    window.onclick = function(event) {
        const modal = document.getElementById('messageModal');
        if (event.target == modal) {
            closeMessageModal();
        }
    }
</script>

<style>
    .message-text {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
    }
    
    .message-text:hover {
        color: #CD602C;
        text-decoration: underline;
    }
    
    #messageModal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
    }
    
    #fullMessage {
        white-space: pre-line;
        line-height: 1.7;
        font-size: 1rem;
    }
</style>
@endsection
