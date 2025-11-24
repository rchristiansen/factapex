<?php
use Factapex\Middleware\CsrfMiddleware;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Factapex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="<?=BASE_PATH?>/public/assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-[linear-gradient(to_bottom_right,#101E32,#101828,#102236)] pt-16">
    <?php include __DIR__ . '/../components/header.php'; ?>

    <section class="min-h-screen py-8">
        <div class="flex flex-col items-center justify-center px-6 mx-auto lg:py-0">
            <div class="w-full rounded-lg shadow-xl sm:max-w-[900px] xl:p-0 bg-gray-800">
                <div class="flex flex-col md:flex-row">
                    <!-- Left side content -->
                    <div class="p-8 md:w-1/2 space-y-6 border-r border-gray-700">
                        <div class="flex items-center space-x-2">
                            <svg class="w-8 h-8 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M11.074 4L14 6.926V16H6V4h5.074zM15 16V6.926L12.074 4H6v12h9z"/>
                            </svg>
                            <span class="text-3xl font-bold text-white">Fact<span class="text-orange-500">apex</span></span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-blue-500 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">Mejora tu flujo de caja</h3>
                                    <p class="text-gray-400">Anticipa el cobro de tus facturas sin afectar tu relación con los clientes.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-blue-500 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">Accede a financiamiento según tus ventas</h3>
                                    <p class="text-gray-400">Solicita factoring en base a tus cuentas por cobrar vigentes.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-blue-500 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">Proceso ágil y transparente</h3>
                                    <p class="text-gray-400">Revisa el estado de tus operaciones en cualquier momento.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right side login form -->
                    <div class="p-8 md:w-1/2">
                        <h1 class="text-2xl font-bold text-white mb-6">
                            Accede a tu cuenta
                        </h1>

                        <form id="loginForm" class="space-y-4">
                            <!-- Token CSRF -->
                            <input type="hidden" name="csrf_token" value="<?= CsrfMiddleware::generateToken() ?>">
                            
                            <div>
                                <input type="email" name="email" id="email" 
                                    class="bg-gray-700 border border-gray-600 text-white sm:text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5"
                                    placeholder="Email" required>
                            </div>
                            <div>
                                <input type="password" name="password" id="password" 
                                    class="bg-gray-700 border border-gray-600 text-white sm:text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5"
                                    placeholder="Contraseña" required>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="remember" type="checkbox" 
                                            class="w-4 h-4 border border-gray-600 rounded bg-gray-700 focus:ring-2 focus:ring-orange-500">
                                    </div>
                                    <label for="remember" class="ml-2 text-sm text-gray-400">Recuérdame</label>
                                </div>
                                <a href="<?=PUBLIC_PATH?>/recover-password" class="text-sm text-orange-500 hover:underline">¿Olvidaste tu Contraseña?</a>
                            </div>
                            
                            <!-- Botón con spinner de Flowbite -->
                            <button type="submit" id="loginBtn" 
                                class="w-full text-white bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center justify-center">
                                <svg id="btnSpinner" class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span id="btnText">Iniciar sesión</span>
                            </button>

                            <div id="loginError" class="hidden bg-red-900/50 border border-red-500/50 text-red-200 px-4 py-3 rounded-lg relative" role="alert"></div>
                            
                            <!-- Divider -->
                            <div class="relative my-6">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-600"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-4 bg-gray-800 text-gray-400 font-medium">
                                        O continúa con
                                    </span>
                                </div>
                            </div>

                            <!-- Google OAuth Button -->
                            <a href="<?=BASE_PATH?>/public/auth/google/login.php" 
                                class="w-full inline-flex items-center justify-center gap-3 bg-white hover:bg-gray-50 text-gray-900 font-medium rounded-lg text-sm px-5 py-2.5 text-center border border-gray-300 shadow-sm transition-all duration-200 hover:shadow-md group">
                                <svg class="w-5 h-5" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                <span class="group-hover:text-gray-700">Continuar con Google</span>
                            </a>

                            <div class="mt-4 p-3 bg-blue-900/20 border border-blue-500/30 rounded-lg">
                                <p class="text-xs text-blue-300 text-center">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    Solo <span class="font-semibold text-blue-200">clientes</span> pueden usar Google OAuth
                                </p>
                            </div>
                            
                            <p class="text-sm font-light text-gray-400 mt-4">
                                ¿No tienes cuenta aún? <a href="<?=PUBLIC_PATH?>/register" class="font-medium text-orange-500 hover:underline">Regístrate aquí</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../components/footer.php'; ?>

    <!-- Scripts al final -->
    <script>
        const BASE_PATH = '<?= BASE_PATH ?>';
        const PUBLIC_PATH = '<?= PUBLIC_PATH ?>';
    </script>
    <script src="<?=BASE_PATH?>/public/assets/js/flowbite.js"></script>
    <script src="<?=BASE_PATH?>/public/assets/js/header.js"></script>
    <script src="<?=BASE_PATH?>/public/assets/js/login.js"></script>
</body>
</html>



