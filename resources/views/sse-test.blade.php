<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Patient Monitor</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .status { padding: 10px; margin: 10px 0; background: #eee; border-radius: 5px; }
        .connected { background: #dfd; }
        .patient-card { border: 2px solid #4CAF50; padding: 15px; margin: 15px 0; border-radius: 8px; }
        .not-found { border-color: #f44336; }
        .message { background: #f9f9f9; padding: 8px; margin: 5px 0; border-left: 3px solid #2196F3; }
    </style>
</head>
<body>
<div class="container">
    <h1>👨‍💼 Patient Monitor</h1>
    <div class="status" id="status">Disconnected</div>

    <div id="patientData">
        <p>Waiting for patient data...</p>
    </div>

    <div id="messages"></div>
</div>

<script>
    // Автоподключение при загрузке
    const eventSource = new EventSource('/sse/stream');

    eventSource.onopen = () => {
        document.getElementById('status').textContent = 'Connected';
        document.getElementById('status').className = 'status connected';
        addMessage('Connected to SSE server');
    };

    eventSource.onerror = (error) => {
        addMessage('Connection error: ' + error.type);
    };

    // Обработка данных пациента
    eventSource.addEventListener('patient_data', (event) => {
        try {
            const data = JSON.parse(event.data);
            displayPatient(data);
            addMessage('Patient data received');
        } catch (e) {
            addMessage('Error parsing data: ' + e.message);
        }
    });

    function displayPatient(data) {
        const container = document.getElementById('patientData');

        if (data.message === 'Patient not found') {
            container.innerHTML = `
                    <div class="patient-card not-found">
                        <h3>❌ Patient Not Found</h3>
                        <p><strong>Searched phone:</strong> ${data.searched_phone}</p>
                        <p><em>${new Date().toLocaleTimeString()}</em></p>
                    </div>
                `;
        } else {
            container.innerHTML = `
                    <div class="patient-card">
                        <h3>✅ ${data.full_name}</h3>
                        <p><strong>ID:</strong> ${data.id}</p>
                        <p><strong>Phone:</strong> ${data.phone_number}</p>
                        <p><strong>Balance:</strong> ${data.balance}</p>
                        <p><strong>Created:</strong> ${new Date(data.created_at).toLocaleDateString()}</p>
                        <p><em>${new Date().toLocaleTimeString()}</em></p>
                    </div>
                `;
        }
    }

    function addMessage(text) {
        const messages = document.getElementById('messages');
        const messageElement = document.createElement('div');
        messageElement.className = 'message';
        messageElement.textContent = `${new Date().toLocaleTimeString()}: ${text}`;
        messages.appendChild(messageElement);
    }
</script>
</body>
</html>
