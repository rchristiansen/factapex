<?php
require_once __DIR__ . '/../../config/app.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Factapex' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="<?=BASE_PATH?>/public/assets/css/output.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/es.js"></script>
</head>
<body class="bg-gray-900 text-gray-100">
    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Mobile Menu Button -->
        <button id="mobileMenuBtn" class="lg:hidden fixed top-4 left-4 z-50 bg-gray-800 text-white p-2 rounded-lg hover:bg-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-gray-800 border-r border-gray-700 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
            <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">Fact<span class="text-orange-500">apex</span></h1>
                    <p class="text-xs text-gray-400 mt-1">Factoring System</p>
                </div>
                <button id="closeSidebar" class="lg:hidden text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <nav class="p-4 space-y-2 overflow-y-auto" style="max-height: calc(100vh - 200px);">
                <button onclick="navigate('<?= PUBLIC_PATH ?>/dashboard')" class="nav-link w-full text-left px-4 py-3 rounded-lg hover:bg-gray-700 transition flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    <span>Dashboard</span>
                </button>
                
                <button onclick="navigate('<?= PUBLIC_PATH ?>/facturas')" class="nav-link w-full text-left px-4 py-3 rounded-lg hover:bg-gray-700 transition text-gray-300 flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Facturas</span>
                </button>
                
                <button onclick="navigate('<?= PUBLIC_PATH ?>/riesgo')" class="nav-link w-full text-left px-4 py-3 rounded-lg hover:bg-gray-700 transition text-gray-300 flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Cuestionario de Riesgo</span>
                </button>
                
                <button onclick="navigate('<?= PUBLIC_PATH ?>/documentos')" class="nav-link w-full text-left px-4 py-3 rounded-lg hover:bg-gray-700 transition text-gray-300 flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Documentos</span>
                </button>
                
                <button onclick="navigate('<?= PUBLIC_PATH ?>/agenda')" class="nav-link w-full text-left px-4 py-3 rounded-lg hover:bg-gray-700 transition text-gray-300 flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Agenda / Recordatorios</span>
                </button>
            </nav>
            
            <div class="absolute bottom-0 w-full p-4 border-t border-gray-700 bg-gray-800">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold">
                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 2)) ?>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></p>
                        <p class="text-xs text-gray-400"><?= ucfirst($_SESSION['user_role'] ?? 'cliente') ?></p>
                    </div>
                </div>
                <a href="<?= PUBLIC_PATH ?>/logout" class="block w-full bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-lg transition text-center font-medium">
                    Cerrar Sesión
                </a>
            </div>
        </aside>

        <!-- Overlay para móvil -->
        <div id="sidebarOverlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-30 hidden"></div>

        <!-- Main Content -->
        <main class="lg:ml-64 min-h-screen">
            <div id="content" class="p-4 lg:p-6">
                <?php include $viewFile; ?>
            </div>
        </main>
    <?php else: ?>
        <!-- Sin sidebar para login/register -->
        <main class="min-h-screen">
            <div id="content">
                <?php include $viewFile; ?>
            </div>
        </main>
    <?php endif; ?>

    <script>
        window.BASE_PATH = '<?= BASE_PATH ?>';
        window.PUBLIC_PATH = '<?= PUBLIC_PATH ?>';
        
        // Función de navegación
        function navigate(url) {
            window.location.href = url;
        }
        
        // Control del menú móvil
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const closeSidebar = document.getElementById('closeSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                });
            }
            
            function closeMenu() {
                if (sidebar) sidebar.classList.add('-translate-x-full');
                if (overlay) overlay.classList.add('hidden');
            }
            
            if (closeSidebar) {
                closeSidebar.addEventListener('click', closeMenu);
            }
            
            if (overlay) {
                overlay.addEventListener('click', closeMenu);
            }
            
            // Cerrar menú al hacer clic en un enlace en móvil
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        closeMenu();
                    }
                });
            });

            // Marcar enlace activo
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                const linkPath = link.getAttribute('onclick');
                if (linkPath && linkPath.includes(currentPath)) {
                    link.classList.add('bg-gray-700', 'text-white');
                    link.classList.remove('text-gray-300');
                }
            });
        });
    </script>
    <script src="<?= BASE_PATH ?>/public/assets/js/flowbite.js"></script>
</body>
</html>