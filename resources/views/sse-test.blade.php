<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSE Тестирование</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .status { padding: 15px; margin: 15px 0; border-radius: 8px; font-weight: bold; }
        .connected { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .disconnected { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .connecting { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .messages { border: 1px solid #ddd; padding: 15px; height: 400px; overflow-y: auto; background: #fafafa; border-radius: 5px; }
        .message { margin: 10px 0; padding: 10px; border-radius: 5px; background: white; }
        .message.info { border-left: 4px solid #007bff; }
        .message.success { border-left: 4px solid #28a745; }
        .message.warning { border-left: 4px solid #ffc107; }
        .message.error { border-left: 4px solid #dc3545; }
        .btn { padding: 12px 20px; margin: 8px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; transition: all 0.3s; }
        .btn-connect { background: #28a745; color: white; }
        .btn-connect:hover { background: #218838; }
        .btn-disconnect { background: #dc3545; color: white; }
        .btn-disconnect:hover { background: #c82333; }
        .btn-send { background: #007bff; color: white; }
        .btn-send:hover { background: #0069d9; }
        .btn-clear { background: #6c757d; color: white; }
        .btn-clear:hover { background: #5a6268; }
        .controls { display: flex; flex-wrap: wrap; margin: 15px 0; }
        input, select { padding: 10px; margin: 5px; border: 1px solid #ddd; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔄 Тестирование SSE уведомлений</h1>

    <div class="status disconnected" id="status">Не подключено</div>

    <div class="controls">
        <button class="btn btn-connect" onclick="connectSSE('simple')">📡 Простое подключение</button>
        <button class="btn btn-connect" onclick="connectSSE('events')">🎯 Подключение с событиями</button>
        <button class="btn btn-disconnect" onclick="disconnectSSE()">⛔ Отключиться</button>
        <button class="btn btn-clear" onclick="clearMessages()">🧹 Очистить</button>
    </div>

    <h3>📨 Сообщения:</h3>
    <div class="messages" id="messages">
        <div class="message info">Готов к работе. Нажмите "Подключение" чтобы начать.</div>
    </div>

    <h3>✉️ Отправка тестового события:</h3>
    <div>
        <input type="text" id="customMessage" placeholder="Введите сообщение" value="Тестовое сообщение">
        <select id="messageType">
            <option value="info">ℹ️ Информация</option>
            <option value="success">✅ Успех</option>
            <option value="warning">⚠️ Предупреждение</option>
            <option value="error">❌ Ошибка</option>
        </select>
        <button class="btn btn-send" onclick="sendTestEvent()">🚀 Отправить</button>
    </div>
</div>

<script>
    let eventSource = null;
    let connectionType = null;

    function connectSSE(type) {
        if (eventSource) {
            addMessage('⚠️', 'Уже подключено!', 'warning');
            return;
        }

        connectionType = type;
        const url = type === 'simple' ? '/sse/simple' : '/sse/events';

        updateStatus('connecting', `Подключаемся к ${url}...`);
        addMessage('📡', `Начинаем подключение к ${url}`, 'info');

        eventSource = new EventSource(url);

        eventSource.onopen = function() {
            updateStatus('connected', `✅ Подключено к ${url}`);
            addMessage('✅', 'Подключение установлено успешно', 'success');
        };

        eventSource.onmessage = function(event) {
            try {
                const data = JSON.parse(event.data);
                addMessage('📨', data.message, data.type || 'info');
            } catch (e) {
                if (event.data.trim() !== 'ping') {
                    addMessage('📨', 'Получены данные: ' + event.data, 'info');
                }
            }
        };

        eventSource.addEventListener('connected', function(event) {
            try {
                const data = JSON.parse(event.data);
                addMessage('✅', data.message, 'success');
            } catch (e) {
                addMessage('✅', 'Подключено к событиям', 'success');
            }
        });

        eventSource.addEventListener('notification', function(event) {
            try {
                const data = JSON.parse(event.data);
                addMessage('🔔', data.message, data.type || 'info');
            } catch (e) {
                addMessage('🔔', event.data, 'info');
            }
        });

        eventSource.onerror = function(error) {
            console.log('SSE connection error (возможно, нормальное закрытие)');
            // Не показываем ошибку пользователю, так как это может быть нормальное закрытие
        };
    }

    function disconnectSSE() {
        if (eventSource) {
            eventSource.close();
            eventSource = null;
            updateStatus('disconnected', '❌ Отключено');
            addMessage('❌', 'Подключение закрыто', 'info');
        }
    }

    function sendTestEvent() {
        const message = document.getElementById('customMessage').value;
        const type = document.getElementById('messageType').value;

        if (!message) {
            addMessage('❌', 'Введите сообщение', 'error');
            return;
        }

        addMessage('📤', 'Отправляем событие...', 'info');

        fetch('/sse/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: message,
                type: type
            })
        })
            .then(response => response.json())
            .then(data => {
                addMessage('✅', 'Событие отправлено на сервер: ' + data.message, 'success');
            })
            .catch(error => {
                addMessage('❌', 'Ошибка отправки: ' + error, 'error');
            });
    }

    function clearMessages() {
        document.getElementById('messages').innerHTML = '';
        addMessage('🧹', 'Сообщения очищены', 'info');
    }

    function addMessage(icon, text, type = 'info') {
        const messages = document.getElementById('messages');
        const messageElement = document.createElement('div');
        messageElement.className = `message ${type}`;
        messageElement.innerHTML = `
                <span style="font-size: 1.2em; margin-right: 10px;">${icon}</span>
                ${text}
                <span style="color: #666; font-size: 0.9em; float: right;">
                    ${new Date().toLocaleTimeString()}
                </span>`;
        messages.appendChild(messageElement);
        messages.scrollTop = messages.scrollHeight;
    }

    function updateStatus(status, text) {
        const statusElement = document.getElementById('status');
        statusElement.className = `status ${status}`;
        statusElement.innerHTML = text;
    }

    // Автоподключение при загрузке страницы
    window.addEventListener('load', function() {
        // Раскомментируйте для автоматического подключения:
        // setTimeout(() => connectSSE('simple'), 1000);
    });

    // Корректное закрытие при покидании страницы
    window.addEventListener('beforeunload', function() {
        if (eventSource) {
            eventSource.close();
        }
    });
</script>
</body>
</html>
