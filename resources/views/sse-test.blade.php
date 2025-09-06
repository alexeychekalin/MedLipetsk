<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Монитор пациентов</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .status-bar {
            background: #34495e;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .status {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        .status.connected { background: #27ae60; }
        .status.connecting { background: #f39c12; }
        .status.disconnected { background: #e74c3c; }
        .content {
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 20px;
            min-height: 600px;
        }
        .patient-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .patient-card h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .patient-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .info-group {
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: 600;
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 1.1em;
            color: #2c3e50;
            word-break: break-word;
        }
        .balance {
            font-size: 1.4em;
            font-weight: bold;
            color: #27ae60;
        }
        .messages-panel {
            background: #ecf0f1;
            border-radius: 10px;
            padding: 20px;
            overflow-y: auto;
            max-height: 600px;
        }
        .messages-panel h3 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .message {
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        .message.patient { border-left-color: #27ae60; }
        .message.error { border-left-color: #e74c3c; }
        .message.warning { border-left-color: #f39c12; }
        .message-time {
            font-size: 0.8em;
            color: #7f8c8d;
            text-align: right;
        }
        .no-patient {
            text-align: center;
            color: #7f8c8d;
            font-style: italic;
            padding: 40px;
        }
        .patient-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
            border: 4px solid #3498db;
        }
        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
            }
            .patient-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>👨‍⚕️ Монитор пациентов</h1>
        <p>Автоматическое отображение данных при входящих звонках</p>
    </div>

    <div class="status-bar">
        <div class="status disconnected" id="status">Отключено</div>
        <div id="connectionInfo">Ожидание подключения...</div>
    </div>

    <div class="content">
        <div class="patient-card">
            <h2>📋 Данные пациента</h2>
            <div id="patientData" class="no-patient">
                <div style="text-align: center; padding: 40px;">
                    <div style="font-size: 3em; margin-bottom: 20px;">👨‍💼</div>
                    <p>Ожидание данных пациента...</p>
                    <p style="font-size: 0.9em; margin-top: 10px;">
                        Данные появятся здесь автоматически при входящем звонке
                    </p>
                </div>
            </div>
        </div>

        <div class="messages-panel">
            <h3>📨 Журнал событий</h3>
            <div id="messages">
                <div class="message">
                    <div>Система запущена и ожидает подключения</div>
                    <div class="message-time" id="currentTime"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let eventSource = null;
    let reconnectAttempts = 0;
    const maxReconnectAttempts = 10;

    // Автоподключение при загрузке страницы
    window.addEventListener('load', function() {
        setTimeout(connectSSE, 1000);
        updateCurrentTime();
        setInterval(updateCurrentTime, 1000);
    });

    function connectSSE() {
        if (eventSource) {
            addMessage('⚠️', 'Уже подключено к SSE', 'warning');
            return;
        }

        updateStatus('connecting', 'Подключение к серверу...');
        addMessage('📡', 'Пытаемся подключиться к серверу SSE', 'info');

        eventSource = new EventSource('/sse/patient-stream');

        eventSource.onopen = function() {
            updateStatus('connected', '✅ Подключено к серверу');
            addMessage('✅', 'Подключение к серверу установлено', 'success');
            reconnectAttempts = 0;
        };

        eventSource.onmessage = function(event) {
            try {
                if (event.data.trim() !== 'ping') {
                    const data = JSON.parse(event.data);
                    addMessage('📨', data.message, 'info');
                }
            } catch (e) {
                console.log('Обычное сообщение:', event.data);
            }
        };

        // Обработка полных данных пациента
        eventSource.addEventListener('patient_full_data', function(event) {
            try {
                const data = JSON.parse(event.data);
                displayPatientData(data);
                addMessage('👨‍💼', `Получены данные пациента: ${data.full_name}`, 'patient');
            } catch (e) {
                addMessage('❌', 'Ошибка разбора данных пациента: ' + e.message, 'error');
            }
        });

        eventSource.addEventListener('connected', function(event) {
            try {
                const data = JSON.parse(event.data);
                addMessage('✅', data.message, 'success');
            } catch (e) {
                addMessage('✅', 'Подключение установлено', 'success');
            }
        });

        eventSource.addEventListener('patient_not_found', function(event) {
            try {
                const data = JSON.parse(event.data);
                clearPatientData();
                addMessage('🔍', `Пациент не найден для номера: ${data.searched_phone}`, 'warning');
            } catch (e) {
                addMessage('❌', 'Ошибка разбора данных: ' + e.message, 'error');
            }
        });

        eventSource.onerror = function(error) {
            updateStatus('disconnected', '❌ Ошибка подключения');

            if (eventSource.readyState === EventSource.CLOSED) {
                addMessage('❌', 'Соединение с сервером разорвано', 'error');
                eventSource.close();
                eventSource = null;

                // Пытаемся переподключиться
                if (reconnectAttempts < maxReconnectAttempts) {
                    reconnectAttempts++;
                    const delay = Math.min(3000 * reconnectAttempts, 30000);
                    addMessage('🔄', `Попытка переподключения через ${delay/1000}сек... (${reconnectAttempts}/${maxReconnectAttempts})`, 'info');

                    setTimeout(connectSSE, delay);
                } else {
                    addMessage('⛔', 'Превышено количество попыток переподключения', 'error');
                }
            }
        };
    }

    function displayPatientData(patient) {
        const patientDiv = document.getElementById('patientData');

        patientDiv.innerHTML = `
                <div style="text-align: center; margin-bottom: 20px;">
                    ${patient.image ?
            `<img src="${patient.image}" alt="Фото пациента" class="patient-image">` :
            '<div style="font-size: 4em; margin-bottom: 10px;">👨‍💼</div>'
        }
                    <h2 style="color: #2c3e50; margin-bottom: 10px;">${patient.full_name}</h2>
                    <div style="color: #7f8c8d; margin-bottom: 20px;">ID: ${patient.id}</div>
                </div>

                <div class="patient-info">
                    <div class="info-group">
                        <div class="info-label">📞 Телефон</div>
                        <div class="info-value">${patient.formatted_phone}</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">💳 Баланс</div>
                        <div class="info-value balance">${patient.formatted_balance}</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">📅 Дата создания</div>
                        <div class="info-value">${new Date(patient.created_at).toLocaleString('ru-RU')}</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">🔄 Последнее обновление</div>
                        <div class="info-value">${new Date(patient.updated_at).toLocaleString('ru-RU')}</div>
                    </div>

                    ${patient.passport ? `
                    <div class="info-group">
                        <div class="info-label">📋 Паспорт</div>
                        <div class="info-value">${patient.passport}</div>
                    </div>
                    ` : ''}

                    ${patient.info ? `
                    <div class="info-group" style="grid-column: 1 / -1;">
                        <div class="info-label">ℹ️ Дополнительная информация</div>
                        <div class="info-value">${patient.info}</div>
                    </div>
                    ` : ''}
                </div>
            `;
    }

    function clearPatientData() {
        const patientDiv = document.getElementById('patientData');
        patientDiv.innerHTML = `
                <div style="text-align: center; padding: 40px;">
                    <div style="font-size: 3em; margin-bottom: 20px;">❌</div>
                    <p>Пациент не найден</p>
                    <p style="font-size: 0.9em; margin-top: 10px;">
                        Проверьте правильность номера телефона
                    </p>
                </div>
            `;
    }

    function addMessage(icon, text, type = 'info') {
        const messages = document.getElementById('messages');
        const messageElement = document.createElement('div');
        messageElement.className = `message ${type}`;
        messageElement.innerHTML = `
                <div>${icon} ${text}</div>
                <div class="message-time">${new Date().toLocaleTimeString()}</div>
            `;
        messages.appendChild(messageElement);
        messages.scrollTop = messages.scrollHeight;
    }

    function updateStatus(status, text) {
        const statusElement = document.getElementById('status');
        statusElement.className = `status ${status}`;
        statusElement.textContent = text;

        const infoElement = document.getElementById('connectionInfo');
        infoElement.textContent = text;
    }

    function updateCurrentTime() {
        document.getElementById('currentTime').textContent = new Date().toLocaleTimeString();
    }

    // Корректное закрытие при покидании страницы
    window.addEventListener('beforeunload', function() {
        if (eventSource) {
            eventSource.close();
        }
    });
</script>
</body>
</html>
