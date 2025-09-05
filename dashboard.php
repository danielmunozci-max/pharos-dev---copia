<?php
// Iniciar sesión y verificar autenticación
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener información del usuario desde la sesión
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_area = $_SESSION['user_area'];
$user_role = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharos S.A.C. - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --secondary: #4f46e5;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --light: #f3f4f6;
            --dark: #374151;
            --sidebar-width: 250px;
            --header-height: 70px;
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
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h2 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .sidebar-header p {
            font-size: 12px;
            opacity: 0.8;
        }
        
        .sidebar-nav {
            flex: 1;
            padding: 20px 0;
        }
        
        .nav-section {
            margin-bottom: 25px;
        }
        
        .nav-title {
            padding: 0 20px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.7;
            margin-bottom: 10px;
        }
        
        .nav-menu {
            list-style: none;
        }
        
        .nav-item {
            margin-bottom: 5px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s;
            position: relative;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: white;
        }
        
        .nav-link i {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        .nav-badge {
            margin-left: auto;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        
        .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 12px;
            text-align: center;
            opacity: 0.7;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .header {
            height: var(--header-height);
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-left {
            display: flex;
            align-items: center;
        }
        
        .toggle-sidebar {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: var(--dark);
            margin-right: 15px;
            display: none;
        }
        
        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .header-action {
            background: none;
            border: none;
            font-size: 18px;
            color: var(--dark);
            cursor: pointer;
            position: relative;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger);
            color: white;
            font-size: 10px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 500;
            font-size: 14px;
        }
        
        .user-role {
            font-size: 12px;
            color: #64748b;
        }
        
        /* Content Area */
        .content {
            flex: 1;
            padding: 25px;
            background-color: #f8fafc;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background-color: #eff6ff;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #64748b;
            font-size: 14px;
        }
        
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .welcome-content h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .welcome-content p {
            opacity: 0.9;
            max-width: 600px;
        }
        
        .welcome-image {
            width: 150px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .welcome-image i {
            font-size: 100px;
            opacity: 0.2;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .toggle-sidebar {
                display: block;
            }
            
            .welcome-banner {
                flex-direction: column;
                text-align: center;
            }
            
            .welcome-image {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Pharos S.A.C.</h2>
            <p>Sistema de Gestión</p>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-title">Principal</div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-chart-line"></i>
                            <span>Estadísticas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Calendario</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="nav-section">
                <div class="nav-title">Operaciones</div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-box"></i>
                            <span>Inventario</span>
                            <span class="nav-badge">12</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-truck"></i>
                            <span>Logística</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Ventas</span>
                            <span class="nav-badge">5</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>Facturación</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="nav-section">
                <div class="nav-title">Clientes</div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span>Gestión de Clientes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-headset"></i>
                            <span>Soporte</span>
                            <span class="nav-badge">3</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-comment-dots"></i>
                            <span>Comunicaciones</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="nav-section">
                <div class="nav-title">Sistema</div>
                  <ul class="nav-menu">
                    <li class="nav-item">

                    <?php if ($user_role == 'Gerente de Desarrollo' || $user_role == 'Gerente de Ventas' || $user_role == 'Administrador') { ?>

                        <li class="nav-item">
    <a href="registrar_empleado.php" class="nav-link">
        <i class="fas fa-user-plus"></i>
        <span>Registrar Empleados</span>
    </a>
</li>
<?php } ?>
                        <a href="#" class="nav-link">
                            <i class="fas fa-cog"></i>
                            <span>Configuración</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-users-cog"></i>
                            <span>Usuarios</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Cerrar Sesión</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <div class="sidebar-footer">
            &copy; <?php echo date('Y'); ?> Pharos S.A.C.
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="header">
            <div class="header-left">
                <button class="toggle-sidebar" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">Dashboard</h1>
            </div>
            
            <div class="header-right">
                <button class="header-action">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                
                <button class="header-action">
                    <i class="fas fa-envelope"></i>
                    <span class="notification-badge">7</span>
                </button>
                
                <div class="user-menu">
                    <div class="user-avatar"><?php echo substr($user_name, 0, 1); ?></div>
                    <div class="user-info">
                        <div class="user-name"><?php echo $user_name; ?></div>
                        <div class="user-role"><?php echo $user_role; ?></div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="content">
            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <div class="welcome-content">
                    <h2>Bienvenido, <?php echo $user_name; ?></h2>
                    <p>Gestiona todas las operaciones de tu empresa desde este panel de control integrado.</p>
                </div>
                <div class="welcome-image">
                    <i class="fas fa-rocket"></i>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="dashboard-grid">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Ventas del Mes</div>
                        <div class="card-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="stat-value">S/ 24,580.00</div>
                    <div class="stat-label">+12% desde el mes pasado</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Clientes Activos</div>
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-value">142</div>
                    <div class="stat-label">+8 este mes</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Órdenes Pendientes</div>
                        <div class="card-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                    <div class="stat-value">18</div>
                    <div class="stat-label">5 urgentes</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Tickets de Soporte</div>
                        <div class="card-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                    </div>
                    <div class="stat-value">24</div>
                    <div class="stat-label">12 sin asignar</div>
                </div>
            </div>
            
            <!-- Additional content can be added here -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Actividad Reciente</div>
                </div>
                <div style="padding: 10px 0;">
                    <p>Aquí irá el contenido del dashboard con gráficos, tablas y otras informaciones relevantes para el usuario.</p>
                    <p>Puedes agregar más secciones según las necesidades específicas de tu sistema.</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            
            if (window.innerWidth < 992 && 
                !sidebar.contains(event.target) && 
                !toggleBtn.contains(event.target) &&
                sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>
</html>