<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar - Pharos S.A.C.</title>
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
        
        .nav-submenu {
            list-style: none;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .nav-submenu.open {
            max-height: 500px;
        }
        
        .nav-subitem {
            margin-bottom: 2px;
        }
        
        .nav-sublink {
            display: flex;
            align-items: center;
            padding: 10px 20px 10px 50px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .nav-sublink:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
        }
        
        .nav-sublink i {
            font-size: 14px;
            width: 20px;
        }
        
        .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 12px;
            text-align: center;
            opacity: 0.7;
        }
        
        .toggle-submenu {
            margin-left: auto;
            transition: transform 0.3s;
        }
        
        .toggle-submenu.open {
            transform: rotate(180deg);
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Pharos S.A.C.</h2>
            <p>Dashboard Gerencial</p>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-title">Principal</div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="dashboard_gerencial.php" class="nav-link active">
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
                <div class="nav-title">Gestión de Personal</div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="#" class="nav-link" id="toggleEmpleados">
                            <i class="fas fa-users"></i>
                            <span>Gestión de Empleados</span>
                            <i class="fas fa-chevron-down toggle-submenu"></i>
                        </a>
                        <ul class="nav-submenu" id="submenuEmpleados">
                            <li class="nav-subitem">
                                <a href="#" class="nav-sublink">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>Registrar Empleado</span>
                                </a>
                            </li>
                            <li class="nav-subitem">
                                <a href="#" class="nav-sublink">
                                    <i class="fas fa-list"></i>
                                    <span>Listar Empleados</span>
                                </a>
                            </li>
                            <li class="nav-subitem">
                                <a href="#" class="nav-sublink">
                                    <i class="fas fa-edit"></i>
                                    <span>Editar Empleados</span>
                                </a>
                            </li>
                            <li class="nav-subitem">
                                <a href="#" class="nav-sublink">
                                    <i class="fas fa-chart-bar"></i>
                                    <span>Reportes de Personal</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user-tie"></i>
                            <span>Gestión de Cargos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-business-time"></i>
                            <span>Horarios y Turnos</span>
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
        <div style="padding: 20px;">
            <h1>Contenido Principal</h1>
            <p>Este es el contenido principal de tu aplicación. El sidebar está fijo a la izquierda.</p>
        </div>
    </main>

    <script>
        // Toggle submenus
        document.getElementById('toggleEmpleados').addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = document.getElementById('submenuEmpleados');
            const icon = this.querySelector('.toggle-submenu');
            
            submenu.classList.toggle('open');
            icon.classList.toggle('open');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            
            if (window.innerWidth < 992 && 
                !sidebar.contains(event.target) &&
                sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        });
        
        // Prevent closing when clicking on toggle buttons
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.querySelector('.toggle-submenu')) {
                    e.stopPropagation();
                }
            });
        });
    </script>
</body>
</html>