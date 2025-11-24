<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <?php if (!empty($user['avatar'])): ?>
                <img src="<?= htmlspecialchars($user['avatar']) ?>" 
                     alt="Avatar" 
                     class="w-16 h-16 rounded-full border-4 border-orange-500 shadow-lg">
            <?php else: ?>
                <div class="w-16 h-16 rounded-full border-4 border-orange-500 bg-gray-700 flex items-center justify-center">
                    <span class="text-2xl font-bold text-white">
                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                    </span>
                </div>
            <?php endif; ?>
            <div>
                <h1 class="text-3xl font-bold text-white">Dashboard Administrador</h1>
                <p class="text-gray-400 mt-1">Panel de control general del sistema</p>
            </div>
        </div>
    </div>

    <!-- Skeleton de carga inicial -->
    <div id="dashboardLoader">
        <!-- Skeleton Cards de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="h-4 bg-gray-700 rounded w-24 mb-3 animate-pulse"></div>
                        <div class="h-8 bg-gray-700 rounded w-16 animate-pulse"></div>
                    </div>
                    <div class="bg-gray-700 p-3 rounded-lg w-12 h-12 animate-pulse"></div>
                </div>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="h-4 bg-gray-700 rounded w-28 mb-3 animate-pulse"></div>
                        <div class="h-8 bg-gray-700 rounded w-16 animate-pulse"></div>
                    </div>
                    <div class="bg-gray-700 p-3 rounded-lg w-12 h-12 animate-pulse"></div>
                </div>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="h-4 bg-gray-700 rounded w-32 mb-3 animate-pulse"></div>
                        <div class="h-8 bg-gray-700 rounded w-16 animate-pulse"></div>
                    </div>
                    <div class="bg-gray-700 p-3 rounded-lg w-12 h-12 animate-pulse"></div>
                </div>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="h-4 bg-gray-700 rounded w-24 mb-3 animate-pulse"></div>
                        <div class="h-8 bg-gray-700 rounded w-20 animate-pulse"></div>
                    </div>
                    <div class="bg-gray-700 p-3 rounded-lg w-12 h-12 animate-pulse"></div>
                </div>
            </div>
        </div>

        <!-- Skeleton Cards grandes -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="h-6 bg-gray-700 rounded w-48 mb-4 animate-pulse"></div>
                <div class="space-y-3">
                    <div class="h-4 bg-gray-700 rounded w-full animate-pulse"></div>
                    <div class="h-4 bg-gray-700 rounded w-3/4 animate-pulse"></div>
                    <div class="h-4 bg-gray-700 rounded w-5/6 animate-pulse"></div>
                </div>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="h-6 bg-gray-700 rounded w-40 mb-4 animate-pulse"></div>
                <div class="space-y-3">
                    <div class="h-4 bg-gray-700 rounded w-full animate-pulse"></div>
                    <div class="h-4 bg-gray-700 rounded w-2/3 animate-pulse"></div>
                    <div class="h-4 bg-gray-700 rounded w-4/5 animate-pulse"></div>
                </div>
            </div>
        </div>

        <!-- Skeleton Acciones rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="bg-gray-700 w-12 h-12 rounded-lg mb-4 animate-pulse"></div>
                <div class="h-5 bg-gray-700 rounded w-24 mb-2 animate-pulse"></div>
                <div class="h-4 bg-gray-700 rounded w-32 animate-pulse"></div>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="bg-gray-700 w-12 h-12 rounded-lg mb-4 animate-pulse"></div>
                <div class="h-5 bg-gray-700 rounded w-28 mb-2 animate-pulse"></div>
                <div class="h-4 bg-gray-700 rounded w-36 animate-pulse"></div>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="bg-gray-700 w-12 h-12 rounded-lg mb-4 animate-pulse"></div>
                <div class="h-5 bg-gray-700 rounded w-20 mb-2 animate-pulse"></div>
                <div class="h-4 bg-gray-700 rounded w-28 animate-pulse"></div>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="bg-gray-700 w-12 h-12 rounded-lg mb-4 animate-pulse"></div>
                <div class="h-5 bg-gray-700 rounded w-24 mb-2 animate-pulse"></div>
                <div class="h-4 bg-gray-700 rounded w-32 animate-pulse"></div>
            </div>
        </div>
    </div>

    <!-- Contenido del dashboard (oculto inicialmente) -->
    <div id="dashboardContent" class="hidden">
        <!-- Cards de estadísticas ADMIN -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Clientes</p>
                    <h3 class="text-2xl font-bold text-white mt-1"><?= $stats['clientes_totales'] ?></h3>
                </div>
                <div class="bg-blue-500/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Facturas Totales</p>
                    <h3 class="text-2xl font-bold text-white mt-1"><?= $stats['facturas_totales'] ?></h3>
                </div>
                <div class="bg-orange-500/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Pendientes Aprobación</p>
                    <h3 class="text-2xl font-bold text-white mt-1"><?= $stats['pendientes_aprobacion'] ?></h3>
                </div>
                <div class="bg-yellow-500/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Volumen Total</p>
                    <h3 class="text-2xl font-bold text-white mt-1">S/ <?= number_format($stats['volumen_total'], 0, ',', ',') ?></h3>
                </div>
                <div class="bg-green-500/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Facturas Pendientes -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-white">Facturas Pendientes de Aprobación</h2>
                <span class="bg-yellow-500/20 text-yellow-500 px-3 py-1 rounded-full text-sm font-medium">
                    <?= $stats['pendientes_aprobacion'] ?>
                </span>
            </div>
            <div class="text-gray-400 text-center py-8">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm">No hay facturas pendientes de aprobación</p>
            </div>
        </div>

        <!-- Nuevos Clientes -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-white">Nuevos Clientes</h2>
                <span class="bg-blue-500/20 text-blue-500 px-3 py-1 rounded-full text-sm font-medium">
                    Esta semana
                </span>
            </div>
            <div class="text-gray-400 text-center py-8">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
                <p class="text-sm">No hay nuevos registros esta semana</p>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas Admin -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <button class="bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-lg p-6 text-left transition">
            <div class="bg-blue-500/20 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                </svg>
            </div>
            <h3 class="text-white font-bold mb-2">Nuevo Cliente</h3>
            <p class="text-gray-400 text-sm">Registrar nuevo cliente</p>
        </button>

        <button class="bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-lg p-6 text-left transition">
            <div class="bg-orange-500/20 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h3 class="text-white font-bold mb-2">Revisar Facturas</h3>
            <p class="text-gray-400 text-sm">Aprobar o rechazar facturas</p>
        </button>

        <button class="bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-lg p-6 text-left transition">
            <div class="bg-green-500/20 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h3 class="text-white font-bold mb-2">Reportes</h3>
            <p class="text-gray-400 text-sm">Generar reportes</p>
        </button>

        <button class="bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-lg p-6 text-left transition">
            <div class="bg-purple-500/20 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h3 class="text-white font-bold mb-2">Configuración</h3>
            <p class="text-gray-400 text-sm">Ajustes del sistema</p>
        </button>
    </div>
    </div>
</div>

<script>
// Simular carga de dashboard con delay
document.addEventListener('DOMContentLoaded', function() {
    const loader = document.getElementById('dashboardLoader');
    const content = document.getElementById('dashboardContent');
    
    // Simular carga de datos (puedes reemplazar con llamadas AJAX reales)
    setTimeout(() => {
        if (loader) loader.classList.add('hidden');
        if (content) content.classList.remove('hidden');
    }, 800); // 800ms de delay para simular carga
});
</script>