<?php
// Iniciar sesión
session_start();

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'pharos_sac');
define('DB_USER', 'root');
define('DB_PASS', '');

// Procesar formulario de login
$error = '';
$email = '';
$debug_info = '';

// Función para crear/resetear usuario administrador
function resetAdminUser($pdo) {
    // Verificar si existe el usuario admin
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM empleados WHERE email = 'admin@pharos.com'");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    // Hash de la contraseña admin123
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    
    if ($count == 0) {
        // Crear usuario administrador
        $stmt = $pdo->prepare("INSERT INTO empleados (area_id, cargo_id, nombre, apellido, email, password_hash, telefono, fecha_contratacion, estado) 
                              VALUES (1, 1, 'Administrador', 'Sistema', 'admin@pharos.com', :password, '000-000-000', CURDATE(), 'Activo')");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        
        return "Usuario administrador creado. Email: admin@pharos.com, Contraseña: admin123";
    } else {
        // Actualizar contraseña del administrador
        $stmt = $pdo->prepare("UPDATE empleados SET password_hash = :password WHERE email = 'admin@pharos.com'");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        
        return "Contraseña de administrador restablecida. Email: admin@pharos.com, Contraseña: admin123";
    }
}

// Procesar solicitud de restablecimiento de contraseña
if (isset($_GET['reset_admin'])) {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $reset_message = resetAdminUser($pdo);
    } catch (PDOException $e) {
        $reset_message = "Error al restablecer administrador: " . $e->getMessage();
    }
}

// Procesar formulario de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validaciones básicas
    if (empty($email) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        try {
            // Crear conexión
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("SET NAMES 'utf8'");
            
            // Buscar usuario por email
            $stmt = $pdo->prepare("SELECT e.*, a.nombre as area_nombre, c.nombre as cargo_nombre 
                                   FROM empleados e 
                                   INNER JOIN areas a ON e.area_id = a.area_id 
                                   INNER JOIN cargos c ON e.cargo_id = c.cargo_id 
                                   WHERE e.email = :email AND e.estado = 'Activo'");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verificar contraseña
                if (password_verify($password, $user['password_hash'])) {
                    // Iniciar sesión
                    $_SESSION['user_id'] = $user['empleado_id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['nombre'] . ' ' . $user['apellido'];
                    $_SESSION['user_area'] = $user['area_nombre'];
                    $_SESSION['user_role'] = $user['cargo_nombre'];
                    
                    // Redirigir a la ventana de carga
                    header("Location: reload.php");
                    exit();
                } else {
                    $error = "Contraseña incorrecta. Intente nuevamente.";
                    $debug_info = "Hash almacenado: " . substr($user['password_hash'], 0, 20) . "...";
                    
                    // Ofrecer restablecer contraseña de administrador
                    if ($email === 'admin@pharos.com') {
                        $debug_info .= "<br><br>¿Es usted el administrador? <a href='?reset_admin=1'>Haga clic aquí para restablecer la contraseña</a>";
                    }
                }
            } else {
                $error = "No existe un usuario activo con ese email.";
                
                // Información de depuración
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM empleados");
                $stmt->execute();
                $total_empleados = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                
                $debug_info = "Total de empleados en la base de datos: " . $total_empleados;
                
                if ($total_empleados == 0) {
                    $debug_info .= "<br><br>No hay usuarios en el sistema. <a href='?reset_admin=1'>Haga clic aquí para crear un usuario administrador</a>";
                }
            }
        } catch (PDOException $e) {
            $error = "Error de conexión a la base de datos: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharos S.A.C. - Sistema de Gestión Integral</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .logo-img {
            width: 180px;
            height: 60px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .logo-img img {
            max-width: 100%;
            max-height: 100%;
        }
        
        .login-header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .login-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .login-form {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            font-size: 14px;
            color: #374151;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon input {
            width: 100%;
            padding: 12px 16px 12px 40px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        .input-with-icon input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }
        
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 16px;
        }
        
        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }
        
        .remember {
            display: flex;
            align-items: center;
        }
        
        .remember input {
            margin-right: 8px;
            accent-color: #2563eb;
        }
        
        .forgot-password {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .login-button {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
        }
        
        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(37, 99, 235, 0.25);
        }
        
        .login-button:active {
            transform: translateY(0);
        }
        
        .support {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #6b7280;
        }
        
        .support a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
        
        .support a:hover {
            text-decoration: underline;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #f3f4f6;
        }
        
        .error-message {
            background: #fef2f2;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            font-size: 14px;
            border-left: 4px solid #dc2626;
        }
        
        .error-message i {
            margin-right: 10px;
            font-size: 16px;
        }
        
        .success-message {
            background: #f0fdf4;
            color: #16a34a;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            font-size: 14px;
            border-left: 4px solid #16a34a;
        }
        
        .success-message i {
            margin-right: 10px;
            font-size: 16px;
        }
        
        .debug-info {
            background: #eff6ff;
            color: #1e40af;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 12px;
            border-left: 4px solid #3b82f6;
        }
        
        .debug-info a {
            color: #2563eb;
            font-weight: 500;
        }
        
        @media (max-width: 480px) {
            .login-container {
                border-radius: 12px;
            }
            
            .login-header {
                padding: 25px 15px;
            }
            
            .login-form {
                padding: 25px 20px;
            }
            
            .options {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .logo-img {
                width: 150px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo-img">
                <img src="https://via.placeholder.com/180x60?text=PHAROS+S.A.C." alt="Logo Pharos S.A.C.">
            </div>
            <h1>Pharos S.A.C.</h1>
            <p>Sistema de gestión integral</p>
        </div>
        
        <div class="login-form">
            <?php if (isset($reset_message)): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo htmlspecialchars($reset_message); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
                
                <?php if (!empty($debug_info)): ?>
                <div class="debug-info">
                    <i class="fas fa-info-circle"></i>
                    <span><?php echo $debug_info; ?></span>
                </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <span>Ha cerrado sesión correctamente.</span>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['session']) && $_GET['session'] == 'expired'): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Su sesión ha expirado. Por favor, ingrese nuevamente.</span>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" required 
                               placeholder="usuario@ejemplo.com" value="<?php echo htmlspecialchars($email); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" required 
                               placeholder="Ingrese su contraseña">
                    </div>
                </div>
                
                <div class="options">
                    <label class="remember">
                        <input type="checkbox" name="remember"> Recordarme
                    </label>
                    <a href="#" class="forgot-password">¿Olvidó su contraseña?</a>
                </div>
                
                <button type="submit" class="login-button">
                    <i class="fas fa-sign-in-alt"></i> Iniciar sesión
                </button>
            </form>
            
            <div class="support">
                ¿Necesita ayuda? <a href="mailto:soporte@pharos.com">Contacte al soporte</a>
            </div>
        </div>
        
        <div class="footer">
            &copy; <?php echo date('Y'); ?> Pharos S.A.C. - Todos los derechos reservados
        </div>
    </div>
</body>
</html>