<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Монитор пациентов - RealTime</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.25);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .status-bar {
            background: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #394b61;
        }

        .status {
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9em;
            background: #e74c3c;
            transition: all 0.3s ease;
        }

        .status.connected {
            background: #27ae60;
            box-shadow: 0 0 15px rgba(39, 174, 96, 0.4);
        }

        .status.connecting {
            background: #f39c12;
        }

        .content {
            display: grid;
            grid-template-columns: 1fr 350px;
            min-height: 500px;
        }

        .patient-section {
            padding: 30px;
            background: #f8f9fa;
        }

        .patient-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border: 2px solid #e9ecef;
        }

        .patient-card h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #3498db;
            font-weight: 400;
        }

        .patient-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-group {
            margin-bottom: 20px;
        }

        .info-label {
            font-weight: 600;
            color: #7f8c8d;
            font-size: 0.85em;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1.1em;
            color: #2c3e50;
            padding: 12px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 8px;
            border: 1px solid #e9ecef;
            min-height: 45px;
            display: flex;
            align-items: center;
        }

        .no-patient {
            text-align: center;
            color: #7f8c8d;
            padding: 60px 30px;
        }

        .no-patient .icon {
            font-size: 4em;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .messages-section {
            background: #ecf0f1;
            padding: 30px;
            border-left: 1px solid #dde4e6;
            overflow-y: auto;
            max-height: 600px;
        }

        .messages-section h3 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-weight: 400;
            padding-bottom: 15px;
            border-bottom: 2px solid #bdc3c7;
        }

        .messages {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .message {
            background: white;
            padding: 15px;
            border-radius: 12px;
            border-left: 4px solid #3498db;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            animation: slideIn 0.3s ease;
        }

        .message.patient {
            border-left-color: #27ae60;
        }

        .message.error {
            border-left-color: #e74c3c;
        }

        .message.warning {
            border-left-color: #f39c12;
        }

        .message-time {
            font-size: 0.75em;
            color: #7f8c8d;
            text-align: right;
            margin-top: 8px;
        }

        .connection-info {
            font-size: 0.9em;
            opacity: 0.8;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
            }

            .messages-section {
                border-left: none;
                border-top: 1px solid #dde4e6;
            }

            .header h1 {
                font-size: 2em;
            }
        }

        .last-update {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>👨‍💼 Монитор пациентов</h1>
        <p>Real-time данные при входящих звонках</p>
    </div>

    <div class="status-bar">
        <div class="status" id="status">ОТКЛЮЧЕНО</div>
        <div class="connection-info" id="connectionInfo">Ожидание подключения к серверу...</div>
    </div>

    <div class="content">
        <div class="patient-section">
            <div class="patient-card">
                <h2>📋 Данные пациента</h2>
                <div id="patientData" class="no-patient">
                    <div class="icon">👨‍💼</div>
                    <h3>Ожидание данных</h3>
                    <p>Данные пациента появятся здесь автоматически при входящем звонке</p>
                </div>
            </div>
            <div class="last-update" id="lastUpdate"></div>
        </div>

        <div class="messages-section">
            <h3>📨 Журнал событий</h3>
            <div class="messages" id="messages">
                <div class="message">
                    <div>Система мониторинга запущена</div>
                    <div class="message-time" id="currentTime"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Глобальные переменные
    let eventSource = null;
    let reconnectAttempts = 0;
    const maxReconnectAttempts = 10;
    const reconnectDelay = 3000;

    // Автоподключение при загрузке страницы
    window.addEventListener('load', function() {
        updateCurrentTime();
        setInterval(updateCurrentTime, 1000);
        setTimeout(connectSSE, 1000);
    });

    // Функция подключения к SSE
    function connectSSE() {
        if (eventSource) {
            addMessage('⚠️ Уже подключено к SSE серверу', 'warning');
            return;
        }

        updateStatus('connecting', 'ПОДКЛЮЧЕНИЕ...');
        addMessage('📡 Подключаемся к SSE серверу...', 'info');

        try {
            eventSource = new EventSource('/sse/simple');

            eventSource.onopen = function() {
                updateStatus('connected', 'ПОДКЛЮЧЕНО');
                addMessage('✅ Подключение к серверу установлено', 'success');
                reconnectAttempts = 0;
            };

            eventSource.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);

                    if (data.type === 'connected') {
                        addMessage('🔗 ' + data.message, 'info');
                    }
                    else if (data.type === 'patient_data' && data.data) {
                        processPatientData(data.data);
                    }
                    else if (data.message) {
                        addMessage('📨 ' + data.message, 'info');
                    }

                } catch (e) {
                    if (event.data.trim() !== 'ping') {
                        addMessage('📨 Получены данные: ' + event.data, 'info');
                    }
                }
            };

            eventSource.onerror = function(error) {
                handleSSEError(error);
            };

        } catch (error) {
            addMessage('❌ Ошибка создания SSE соединения: ' + error.message, 'error');
            attemptReconnect();
        }
    }

    // Обработка данных пациента
    function processPatientData(patientData) {
        if (patientData.message === 'Пациент не найден') {
            displayNoPatient(patientData.searched_phone);
            addMessage('❌ Пациент не найден для номера: ' + patientData.searched_phone, 'warning');
        } else {
            displayPatientData(patientData);
            addMessage('✅ Получены данные пациента: ' +
                (patientData.second_name || '') + ' ' +
                (patientData.first_name || ''), 'patient');
        }
    }

    // Отображение данных пациента
    function displayPatientData(patient) {
        const container = document.getElementById('patientData');

        container.innerHTML = `
                <div class="patient-info">
                    <div class="info-group">
                        <div class="info-label">📞 Номер телефона</div>
                        <div class="info-value">${patient.phone_number || 'Не указан'}</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">👨 Фамилия</div>
                        <div class="info-value">${patient.second_name || 'Не указана'}</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">👦 Имя</div>
                        <div class="info-value">${patient.first_name || 'Не указано'}</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">👴 Отчество</div>
                        <div class="info-value">${patient.patronymic_name || 'Не указано'}</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">💳 Баланс</div>
                        <div class="info-value">${patient.balance || '0.00'} ₽</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">🆔 ID пациента</div>
                        <div class="info-value">${patient.id || 'Не указан'}</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">📅 Дата создания</div>
                        <div class="info-value">${patient.created_at ? new Date(patient.created_at).toLocaleString('ru-RU') : 'Не указана'}</div>
                    </div>
                </div>
            `;

        updateLastUpdate();
    }

    // Отображение "пациент не найден"
    function displayNoPatient(phone) {
        const container = document.getElementById('patientData');
        container.innerHTML = `
                <div class="no-patient">
                    <div class="icon">❌</div>
                    <h3>Пациент не найден</h3>
                    <p>По номеру: <strong>${phone}</strong></p>
                    <p style="margin-top: 10px; font-size: 0.9em;">
                        Проверьте правильность номера телефона
                    </p>
                </div>
            `;
        updateLastUpdate();
    }

    // Обновление времени последнего обновления
    function updateLastUpdate() {
        document.getElementById('lastUpdate').textContent =
            'Последнее обновление: ' + new Date().toLocaleTimeString();
    }

    // Обработка ошибок SSE
    function handleSSEError(error) {
        updateStatus('disconnected', 'ОТКЛЮЧЕНО');

        if (eventSource && eventSource.readyState === EventSource.CLOSED) {
            addMessage('❌ Соединение с сервером разорвано', 'error');
            if (eventSource) {
                eventSource.close();
                eventSource = null;
            }
            attemptReconnect();
        }
    }

    // Попытка переподключения
    function attemptReconnect() {
        if (reconnectAttempts < maxReconnectAttempts) {
            reconnectAttempts++;
            const delay = Math.min(reconnectDelay * reconnectAttempts, 30000);

            addMessage(`🔄 Попытка переподключения через ${delay/1000}сек... (${reconnectAttempts}/${maxReconnectAttempts})`, 'info');

            setTimeout(() => {
                if (!eventSource) {
                    connectSSE();
                }
            }, delay);
        } else {
            addMessage('⛔ Превышено количество попыток переподключения', 'error');
        }
    }

    // Добавление сообщения в журнал
    function addMessage(text, type = 'info') {
        const messagesContainer = document.getElementById('messages');
        const messageElement = document.createElement('div');
        messageElement.className = `message ${type}`;
        messageElement.innerHTML = `
                <div>${text}</div>
                <div class="message-time">${new Date().toLocaleTimeString()}</div>
            `;
        messagesContainer.appendChild(messageElement);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Обновление статуса подключения
    function updateStatus(status, text) {
        const statusElement = document.getElementById('status');
        statusElement.textContent = text;
        statusElement.className = `status ${status}`;

        document.getElementById('connectionInfo').textContent =
            status === 'connected' ? 'Соединение активно' :
                status === 'connecting' ? 'Установка соединения...' :
                    'Соединение разорвано';
    }

    // Обновление текущего времени
    function updateCurrentTime() {
        document.getElementById('currentTime').textContent =
            new Date().toLocaleTimeString('ru-RU');
    }

    // Закрытие соединения при выходе
    window.addEventListener('beforeunload', function() {
        if (eventSource) {
            eventSource.close();
            addMessage('📴 Соединение закрыто', 'info');
        }
    });

    // Функции для тестирования (вызов из консоли браузера)
    window.testPatient = function() {
        const testData = {
            id: 'test-123456',
            phone_number: '+7 (920) 123-45-67',
            second_name: 'Иванов',
            first_name: 'Иван',
            patronymic_name: 'Иванович',
            balance: '1500.50',
            created_at: new Date().toISOString()
        };
        displayPatientData(testData);
        addMessage('✅ Тестовые данные загружены', 'patient');
    };

    window.testNotFound = function() {
        displayNoPatient('+7 (999) 999-99-99');
        addMessage('❌ Тест: пациент не найден', 'warning');
    };

    window.clearMessages = function() {
        document.getElementById('messages').innerHTML = '';
        addMessage('🧹 Журнал очищен', 'info');
    };
</script>
</body>
</html>
