<?php
// Iniciar sesión y verificar autenticación
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharos S.A.C. - Cargando</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }
        
        .loading-container {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 90%;
        }
        
        .logo {
            margin-bottom: 30px;
        }
        
        .logo h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .logo p {
            opacity: 0.8;
        }
        
        .spinner {
            width: 60px;
            height: 60px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin: 0 auto 30px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .progress-bar {
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .progress {
            height: 100%;
            width: 0%;
            background: white;
            border-radius: 3px;
            animation: progress 2s ease-in-out forwards;
        }
        
        @keyframes progress {
            to { width: 100%; }
        }
        
        .message {
            margin-top: 20px;
            font-size: 14px;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="loading-container">
        <div class="logo">
            <h1>Pharos S.A.C.</h1>
            <p>Sistema de gestión integral</p>
        </div>
        
        <div class="spinner"></div>
        
        <div class="progress-bar">
            <div class="progress"></div>
        </div>
        
        <h2>Inicializando sistema</h2>
        <p class="message">Cargando módulos y configuraciones...</p>
    </div>

    <script>
        // Redirigir al dashboard después de 3 segundos
        setTimeout(function() {
            window.location.href = "dashboard.php";
        }, 3000);
        
        // Mensajes cambiantes durante la carga
        const messages = [
            "Cargando módulos y configuraciones...",
            "Optimizando rendimiento...",
            "Preparando interfaz de usuario...",
            "¡Todo listo! Redirigiendo..."
        ];
        
        let currentMessage = 0;
        const messageElement = document.querySelector('.message');
        
        // Cambiar mensaje cada 0.8 segundos
        const messageInterval = setInterval(() => {
            currentMessage = (currentMessage + 1) % messages.length;
            messageElement.textContent = messages[currentMessage];
            
            // Detener el intervalo después de 2.5 segundos
            if (currentMessage === messages.length - 1) {
                setTimeout(() => {
                    clearInterval(messageInterval);
                }, 800);
            }
        }, 800);
    </script>
</body>
</html>