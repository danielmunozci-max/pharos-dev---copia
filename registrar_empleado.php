<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si el usuario tiene permisos de gerente
$user_role = $_SESSION['user_role'];
$allowed_roles = ['Gerente de Desarrollo', 'Gerente de Ventas', 'Administrador'];
if (!in_array($user_role, $allowed_roles)) {
    header("Location: dashboard.php");
    exit();
}

// Incluir configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'pharos_sac');
define('DB_USER', 'root');
define('DB_PASS', '');

$error = '';
$success = '';

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $area_id = $_POST['area_id'];
    $cargo_id = $_POST['cargo_id'];
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $telefono = trim($_POST['telefono']);
    $fecha_contratacion = $_POST['fecha_contratacion'];

    // Validaciones básicas
    if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
        $error = "Los campos obligatorios no pueden estar vacíos.";
    } else {
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verificar si el email ya existe
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM empleados WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $error = "El email ya está registrado.";
            } else {
                // Hash de la contraseña
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insertar empleado
                $stmt = $pdo->prepare("INSERT INTO empleados (area_id, cargo_id, nombre, apellido, email, password_hash, telefono, fecha_contratacion, estado) 
                                       VALUES (:area_id, :cargo_id, :nombre, :apellido, :email, :password_hash, :telefono, :fecha_contratacion, 'Activo')");
                $stmt->bindParam(':area_id', $area_id);
                $stmt->bindParam(':cargo_id', $cargo_id);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':apellido', $apellido);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password_hash', $hashedPassword);
                $stmt->bindParam(':telefono', $telefono);
                $stmt->bindParam(':fecha_contratacion', $fecha_contratacion);
                $stmt->execute();

                $success = "Empleado registrado correctamente.";
            }
        } catch (PDOException $e) {
            $error = "Error al registrar el empleado: " . $e->getMessage();
        }
    }
}

// Obtener áreas y cargos para los dropdowns
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM areas");
    $areas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT * FROM cargos");
    $cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al cargar datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Empleado - Pharos S.A.C.</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos generales (usar los mismos que en dashboard) */
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --secondary: #4f46e5;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --light: #f3f4f6;
            --dark: #374151;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            color: #334155;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .card-header {
            margin-bottom: 20px;
        }
        
        .card-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }
        
        .btn {
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        
        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-error {
            background-color: #fef2f2;
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }
        
        .alert-success {
            background-color: #f0fdf4;
            color: var(--success);
            border-left: 4px solid var(--success);
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?> <!-- Asumiendo que tenemos un sidebar reutilizable -->

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Registrar Nuevo Empleado</h1>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="grid">
                    <div class="form-group">
                        <label for="nombre">Nombres *</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>

                    <div class="form-group">
                        <label for="apellido">Apellidos *</label>
                        <input type="text" id="apellido" name="apellido" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Correo Electrónico *</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña *</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono">
                    </div>

                    <div class="form-group">
                        <label for="fecha_contratacion">Fecha de Contratación</label>
                        <input type="date" id="fecha_contratacion" name="fecha_contratacion">
                    </div>

                    <div class="form-group">
                        <label for="area_id">Área *</label>
                        <select id="area_id" name="area_id" required>
                            <option value="">Seleccionar Área</option>
                            <?php foreach ($areas as $area): ?>
                                <option value="<?php echo $area['area_id']; ?>"><?php echo $area['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cargo_id">Cargo *</label>
                        <select id="cargo_id" name="cargo_id" required>
                            <option value="">Seleccionar Cargo</option>
                            <?php foreach ($cargos as $cargo): ?>
                                <option value="<?php echo $cargo['cargo_id']; ?>"><?php echo $cargo['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Registrar Empleado</button>
            </form>
        </div>
    </div>
</body>
</html>