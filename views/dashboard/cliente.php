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
                <h1 class="text-3xl font-bold text-white">Dashboard Cliente</h1>
                <p class="text-gray-400 mt-1">Bienvenido, <?= htmlspecialchars($user['name']) ?></p>
            </div>
        </div>
    </div>

    <!-- Cards de estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Mis Facturas</p>
                    <h3 class="text-2xl font-bold text-white mt-1"><?= $stats['facturas_totales'] ?></h3>
                </div>
                <div class="bg-blue-500/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">En Revisión</p>
                    <h3 class="text-2xl font-bold text-white mt-1"><?= $stats['en_revision'] ?></h3>
                </div>
                <div class="bg-orange-500/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Aprobadas</p>
                    <h3 class="text-2xl font-bold text-white mt-1"><?= $stats['aprobadas'] ?></h3>
                </div>
                <div class="bg-green-500/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Financiado</p>
                    <h3 class="text-2xl font-bold text-white mt-1">S/ <?= number_format($stats['monto_total'], 0, ',', ',') ?></h3>
                </div>
                <div class="bg-purple-500/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimas Facturas -->
    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-white">Mis Últimas Facturas</h2>
            <button onclick="navigate('<?= PUBLIC_PATH ?>/facturas')" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                Ver todas
            </button>
        </div>
        <div class="text-gray-400 text-center py-8">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
            </svg>
            <p>No tienes facturas registradas</p>
            <p class="text-sm mt-2">Comienza subiendo tu primera factura</p>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <button onclick="navigate('<?= PUBLIC_PATH ?>/facturas')" class="bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-lg p-6 text-left transition">
            <div class="bg-blue-500/20 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h3 class="text-white font-bold mb-2">Cargar Factura</h3>
            <p class="text-gray-400 text-sm">Sube una nueva factura para financiamiento</p>
        </button>

        <button onclick="navigate('<?= PUBLIC_PATH ?>/riesgo')" class="bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-lg p-6 text-left transition">
            <div class="bg-orange-500/20 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h3 class="text-white font-bold mb-2">Cuestionario de Riesgo</h3>
            <p class="text-gray-400 text-sm">Completa tu evaluación de riesgo</p>
        </button>

        <button onclick="navigate('<?= PUBLIC_PATH ?>/documentos')" class="bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-lg p-6 text-left transition">
            <div class="bg-green-500/20 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h3 class="text-white font-bold mb-2">Documentos</h3>
            <p class="text-gray-400 text-sm">Gestiona tus documentos adjuntos</p>
        </button>
    </div>
</div>