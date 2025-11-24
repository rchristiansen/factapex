<?php
/**
 * Mostrar formulario para completar perfil de usuario Google
 * SIN COMPOSER - Acceso directo
 */

session_start();

// Cargar configuración
require_once __DIR__ . '/../../../config/app.php';

// Verificar que hay datos de Google en sesión
if (!isset($_SESSION['google_user_data'])) {
    header('Location: ' . PUBLIC_PATH . '/login');
    exit;
}

$googleData = $_SESSION['google_user_data'];

// Cargar CSRF Middleware
require_once __DIR__ . '/../../../src/Middleware/CsrfMiddleware.php';
use Factapex\Middleware\CsrfMiddleware;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Perfil - Factapex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="<?=BASE_PATH?>/public/assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-[linear-gradient(to_bottom_right,#101E32,#101828,#102236)]">

    <section class="min-h-screen py-8 flex items-center">
        <div class="flex flex-col items-center justify-center px-6 mx-auto w-full">
            <div class="w-full rounded-lg shadow-xl sm:max-w-md xl:p-0 bg-gray-800">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <!-- Avatar de Google -->
                    <div class="flex flex-col items-center space-y-4">
                        <?php if (!empty($googleData['picture'])): ?>
                            <img src="<?= htmlspecialchars($googleData['picture']) ?>" 
                                 alt="Avatar" 
                                 class="w-20 h-20 rounded-full border-4 border-orange-500">
                        <?php endif; ?>
                        <div class="text-center">
                            <h1 class="text-xl font-bold text-white">
                                ¡Bienvenido!
                            </h1>
                            <p class="text-gray-400 text-sm mt-1">
                                <?= htmlspecialchars($googleData['email']) ?>
                            </p>
                            <p class="text-gray-500 text-sm mt-2">
                                Por favor completa tu perfil para continuar
                            </p>
                        </div>
                    </div>

                    <!-- Errores -->
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="p-4 mb-4 text-sm text-red-400 rounded-lg bg-red-900/50 border border-red-700" role="alert">
                            <div class="flex items-center">
                                <svg class="shrink-0 inline w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium"><?= htmlspecialchars($_SESSION['error_message']) ?></span>
                            </div>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>

                    <!-- Formulario -->
                    <form class="space-y-4 md:space-y-6" action="/factapex/public/auth/google/complete-profile.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= CsrfMiddleware::generateToken() ?>">
                        
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block mb-2 text-sm font-medium text-white">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   value="<?= htmlspecialchars($googleData['given_name'] ?? '') ?>"
                                   class="border sm:text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white" 
                                   placeholder="Juan" 
                                   required>
                        </div>

                        <!-- Apellido -->
                        <div>
                            <label for="apellido" class="block mb-2 text-sm font-medium text-white">
                                Apellido <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="apellido" 
                                   id="apellido" 
                                   value="<?= htmlspecialchars($googleData['family_name'] ?? '') ?>"
                                   class="border sm:text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white" 
                                   placeholder="Pérez" 
                                   required>
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-white">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="border sm:text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white" 
                                   placeholder="••••••••" 
                                   required
                                   minlength="6">
                            <p class="mt-1 text-xs text-gray-400">Mínimo 6 caracteres</p>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="password_confirm" class="block mb-2 text-sm font-medium text-white">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirm" 
                                   id="password_confirm" 
                                   class="border sm:text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white" 
                                   placeholder="••••••••" 
                                   required
                                   minlength="6">
                        </div>

                        <!-- Teléfono (opcional) -->
                        <div>
                            <label for="telefono" class="block mb-2 text-sm font-medium text-white">
                                Teléfono <span class="text-gray-500">(opcional)</span>
                            </label>
                            <input type="tel" 
                                   name="telefono" 
                                   id="telefono" 
                                   class="border sm:text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white" 
                                   placeholder="+56 9 1234 5678">
                        </div>

                        <!-- Botón Submit -->
                        <button type="submit" 
                                class="w-full text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
                            Completar Registro
                        </button>

                        <!-- Nota informativa -->
                        <div class="text-center">
                            <p class="text-xs text-gray-400">
                                Al completar tu perfil, podrás iniciar sesión con Google o con tu email y contraseña.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Validar que las contraseñas coincidan
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
        });
    </script>
</body>
</html>
