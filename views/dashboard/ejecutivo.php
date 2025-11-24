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
                <h1 class="text-3xl font-bold text-white">Dashboard Ejecutivo</h1>
                <p class="text-gray-400 mt-1">Panel de gestión de operaciones y clientes</p>
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

        <!-- Skeleton tabla clientes -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 mb-6">
            <div class="h-6 bg-gray-700 rounded w-40 mb-4 animate-pulse"></div>
            <div class="space-y-3">
                <div class="h-4 bg-gray-700 rounded w-full animate-pulse"></div>
                <div class="h-4 bg-gray-700 rounded w-3/4 animate-pulse"></div>
                <div class="h-4 bg-gray-700 rounded w-5/6 animate-pulse"></div>
            </div>
        </div>
    </div>

    <!-- Contenido del dashboard (oculto inicialmente) -->
    <div id="dashboardContent" class="hidden">
        <!-- Cards de estadísticas EJECUTIVO -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Mis Clientes</p>
                        <h3 class="text-2xl font-bold text-white mt-1"><?= $stats['clientes_asignados'] ?></h3>
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
                        <p class="text-gray-400 text-sm">Facturas Gestionadas</p>
                        <h3 class="text-2xl font-bold text-white mt-1"><?= $stats['facturas_gestionadas'] ?></h3>
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
                        <p class="text-gray-400 text-sm">En Proceso</p>
                        <h3 class="text-2xl font-bold text-white mt-1"><?= $stats['en_proceso'] ?></h3>
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
                        <p class="text-gray-400 text-sm">Volumen Gestionado</p>
                        <h3 class="text-2xl font-bold text-white mt-1">$<?= number_format($stats['volumen_gestionado'], 0, ',', ',') ?></h3>
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

        <!-- Clientes Asignados -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-white">Mis Clientes Asignados</h2>
                <button onclick="navigate('<?= PUBLIC_PATH ?>/clientes')" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Ver todos
                </button>
            </div>
            <div class="text-gray-400 text-center py-8">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
                <p>No tienes clientes asignados</p>
                <p class="text-sm mt-2">Los clientes aparecerán aquí cuando sean asignados por el administrador</p>
            </div>
        </div>

        <!-- Acciones Rápidas Ejecutivo -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <button onclick="navigate('<?= PUBLIC_PATH ?>/facturas')" class="bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-lg p-6 text-left transition">
                <div class="bg-orange-500/20 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-white font-bold mb-2">Gestionar Facturas</h3>
                <p class="text-gray-400 text-sm">Revisar y procesar facturas</p>
            </button>

            <button onclick="navigate('<?= PUBLIC_PATH ?>/clientes')" class="bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-lg p-6 text-left transition">
                <div class="bg-blue-500/20 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                </div>
                <h3 class="text-white font-bold mb-2">Mis Clientes</h3>
                <p class="text-gray-400 text-sm">Ver clientes asignados</p>
            </button>

            <button onclick="navigate('<?= PUBLIC_PATH ?>/documentos')" class="bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-lg p-6 text-left transition">
                <div class="bg-green-500/20 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-white font-bold mb-2">Documentos</h3>
                <p class="text-gray-400 text-sm">Gestionar documentación</p>
            </button>

            <button onclick="navigate('<?= PUBLIC_PATH ?>/reportes')" class="bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-lg p-6 text-left transition">
                <div class="bg-purple-500/20 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                <h3 class="text-white font-bold mb-2">Reportes</h3>
                <p class="text-gray-400 text-sm">Ver métricas y reportes</p>
            </button>
        </div>
    </div>
</div>

<script>
// Simular carga de dashboard con delay
document.addEventListener('DOMContentLoaded', function() {
    const loader = document.getElementById('dashboardLoader');
    const content = document.getElementById('dashboardContent');
    
    // Simular carga de datos
    setTimeout(() => {
        if (loader) loader.classList.add('hidden');
        if (content) content.classList.remove('hidden');
    }, 800);
});
</script>
