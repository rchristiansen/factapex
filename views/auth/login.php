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
                            
                            <p class="text-sm font-light text-gray-400">
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



