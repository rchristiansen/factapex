<?php
/**
 * Test simple de Google OAuth
 * Verifica que todo esté configurado correctamente
 */

echo "🔍 Verificando configuración de Google OAuth...\n\n";

// 1. Verificar PHP
echo "✓ PHP Version: " . PHP_VERSION . "\n";

// 2. Verificar cURL
echo "✓ cURL: " . (function_exists('curl_init') ? 'Habilitado' : '❌ NO DISPONIBLE') . "\n";

// 3. Cargar configuración
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/google_oauth.php';

echo "✓ Config cargada\n";

// 4. Verificar credenciales
echo "\n📋 Credenciales:\n";
echo "   Client ID: " . (GOOGLE_CLIENT_ID !== 'TU_GOOGLE_CLIENT_ID_AQUI' ? '✓ Configurado' : '❌ Sin configurar') . "\n";
echo "   Client Secret: " . (GOOGLE_CLIENT_SECRET !== 'TU_GOOGLE_CLIENT_SECRET_AQUI' ? '✓ Configurado' : '❌ Sin configurar') . "\n";
echo "   Redirect URI: " . GOOGLE_REDIRECT_URI . "\n";

// 5. Test de cURL a Google
echo "\n🌐 Test de conexión a Google:\n";
$ch = curl_init('https://accounts.google.com/o/oauth2/v2/auth');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   HTTP Status: " . $httpCode . ($httpCode == 200 || $httpCode == 302 || $httpCode == 405 ? ' ✓' : ' ❌') . "\n";

// 6. Verificar archivos OAuth
echo "\n📁 Archivos OAuth:\n";
$files = [
    'src/Services/GoogleOAuthService.php',
    'public/auth/google/login.php',
    'public/auth/google/callback.php'
];

foreach ($files as $file) {
    $path = __DIR__ . '/../' . $file;
    echo "   " . $file . ": " . (file_exists($path) ? '✓' : '❌ No existe') . "\n";
}

// 7. Test de URL de autorización
echo "\n🔗 URL de autorización generada:\n";
require_once __DIR__ . '/../src/Core/Database.php';
require_once __DIR__ . '/../src/Models/User.php';
require_once __DIR__ . '/../src/Services/GoogleOAuthService.php';

try {
    $service = new \Factapex\Services\GoogleOAuthService();
    $authUrl = $service->getAuthUrl();
    echo "   " . substr($authUrl, 0, 100) . "...\n";
    echo "   ✓ Servicio OAuth funcional\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n✅ Verificación completa\n";
echo "\n💡 Próximo paso: Abre http://localhost/factapex/public/auth/google/login en tu navegador\n";
