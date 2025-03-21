@extends('layouts.adm')

@section('content')
@php
    // Если контроллер не передал переменную $users, получаем всех пользователей
    $users = isset($users) && $users->count() ? $users : \App\Models\User::all();
@endphp
<div class="admin-container">
    <h1 class="admin-title wow fadeInDown">Управление пользователями</h1>
    
    @if(session('success'))
        <div class="admin-alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="admin-card wow fadeInUp">
        <div class="header-section flex-between">
            <h5>Список пользователей</h5>
            <button class="admin-btn" onclick="showModal('addUserModal')">Добавить пользователя</button>
        </div>
        <div id="usersTable_wrapper" class="admin-dataTables-wrapper">
            <div class="admin-table-wrapper">
                <table class="admin-table" id="usersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Дата регистрации</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users ?? [] as $user)
                        <tr>
                            <td>{{ $user->id ?? '1' }}</td>
                            <td>{{ $user->name ?? 'Александр Иванов' }}</td>
                            <td>{{ $user->email ?? 'user@example.com' }}</td>
                            <td>
                                <span class="admin-badge {{ ($user->status ?? 'user') == 'admin' ? 'badge-danger' : 'badge-info' }}">
                                    {{ ($user->status ?? 'user') == 'admin' ? 'Администратор' : 'Пользователь' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at ?? '2023-06-15' }}</td>
                            <td class="actions">
                                <button class="admin-btn admin-btn--small edit-user-btn" data-id="{{ $user->id ?? '1' }}">Редактировать</button>
                                <button class="admin-btn admin-btn--small admin-btn--danger delete-user-btn" data-id="{{ $user->id ?? '1' }}">Удалить</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для добавления пользователя -->
<div class="custom-modal" id="addUserModal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Добавить пользователя</h5>
            <span class="custom-modal-close">&times;</span>
        </div>
        <div class="custom-modal-body">
            <form id="addUserForm">
                <div class="admin-form-group">
                    <label for="name" class="admin-label">Имя</label>
                    <input type="text" class="admin-input" id="name" name="name" required>
                </div>
                <div class="admin-form-group">
                    <label for="email" class="admin-label">Email</label>
                    <input type="email" class="admin-input" id="email" name="email" required>
                </div>
                <div class="admin-form-group">
                    <label for="password" class="admin-label">Пароль</label>
                    <input type="password" class="admin-input" id="password" name="password" required>
                </div>
                <div class="admin-form-group">
                    <label for="role" class="admin-label">Роль</label>
                    <select class="admin-select" id="role" name="role">
                        <option value="user">Пользователь</option>
                        <option value="admin">Администратор</option>
                    </select>
                </div>
                <div class="flex-end">
                    <button type="submit" class="admin-btn">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно для редактирования пользователя -->
<div class="custom-modal" id="editUserModal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Редактировать пользователя</h5>
            <span class="custom-modal-close">&times;</span>
        </div>
        <div class="custom-modal-body">
            <form id="editUserForm">
                <input type="hidden" id="edit_id" name="id">
                <div class="admin-form-group">
                    <label for="edit_name" class="admin-label">Имя</label>
                    <input type="text" class="admin-input" id="edit_name" name="name" required>
                </div>
                <div class="admin-form-group">
                    <label for="edit_email" class="admin-label">Email</label>
                    <input type="email" class="admin-input" id="edit_email" name="email" required>
                </div>
                <div class="admin-form-group">
                    <label for="edit_password" class="admin-label">Новый пароль (если нужно сменить)</label>
                    <input type="password" class="admin-input" id="edit_password" name="password">
                </div>
                <div class="admin-form-group">
                    <label for="edit_role" class="admin-label">Роль</label>
                    <select class="admin-select" id="edit_role" name="role">
                        <option value="user">Пользователь</option>
                        <option value="admin">Администратор</option>
                    </select>
                </div>
                <div class="flex-end">
                    <button type="submit" class="admin-btn">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно подтверждения удаления -->
<div class="custom-modal" id="deleteUserModal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Подтверждение удаления</h5>
            <span class="custom-modal-close">&times;</span>
        </div>
        <div class="custom-modal-body">
            <p>Вы действительно хотите удалить пользователя <strong id="deleteUserName"></strong>?</p>
        </div>
        <div class="custom-modal-footer">
            <button class="admin-btn" onclick="hideModal('deleteUserModal')">Отмена</button>
            <button id="confirmDeleteBtn" class="admin-btn admin-btn--danger">Удалить</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Инициализация DataTables
        $('#usersTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/ru.json'
            }
        });

        // Обработчик для кнопки редактирования
        $('.edit-user-btn').click(function() {
            const userId = $(this).data('id');
            // Здесь должен быть AJAX запрос для получения данных пользователя
            // Для демонстрации используем фиктивные данные
            const userData = {
                id: userId,
                name: userId == 1 ? 'Александр Иванов' : (userId == 2 ? 'Мария Петрова' : 'Дмитрий Сидоров'),
                email: userId == 1 ? 'user@example.com' : (userId == 2 ? 'maria@example.com' : 'dmitry@example.com'),
                role: userId == 3 ? 'admin' : 'user'
            };

            $('#edit_id').val(userData.id);
            $('#edit_name').val(userData.name);
            $('#edit_email').val(userData.email);
            $('#edit_role').val(userData.role);
            
            showModal('editUserModal');
        });

        // Обработчик для кнопки удаления
        $('.delete-user-btn').click(function() {
            const userId = $(this).data('id');
            // Получаем имя пользователя из ряда таблицы
            const userName = $(this).closest('tr').find('td:eq(1)').text();
            
            $('#deleteUserName').text(userName);
            $('#confirmDeleteBtn').data('id', userId);
            
            showModal('deleteUserModal');
        });
        
        // Подтверждение удаления
        $('#confirmDeleteBtn').click(function() {
            const userId = $(this).data('id');
            
            // Здесь должен быть AJAX запрос для удаления пользователя
            // Для демонстрации просто показываем алерт
            alert('Пользователь с ID ' + userId + ' был удален.');
            
            // Скрываем модальное окно
            hideModal('deleteUserModal');
            
            // После успешного удаления можно обновить таблицу
            // или удалить соответствующую строку
            // $('tr[data-id="' + userId + '"]').remove();
        });

        // Обработка формы добавления пользователя
        $('#addUserForm').submit(function(e) {
            e.preventDefault();
            // Здесь должен быть AJAX запрос для добавления пользователя
            alert('Пользователь успешно добавлен!');
            hideModal('addUserModal');
            
            // Сбрасываем форму
            this.reset();
            
            // После успешного добавления обновляем таблицу или перезагружаем страницу
        });

        // Обработка формы редактирования пользователя
        $('#editUserForm').submit(function(e) {
            e.preventDefault();
            // Здесь должен быть AJAX запрос для обновления данных пользователя
            alert('Данные пользователя успешно обновлены!');
            hideModal('editUserModal');
            
            // После успешного обновления обновляем таблицу или перезагружаем страницу
        });
    });
</script>
@endsection
