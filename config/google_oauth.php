<?php
/**
 * Configuración de Google OAuth 2.0
 * 
 * INSTRUCCIONES PARA CONFIGURAR:
 * 
 * 1. Ve a https://console.cloud.google.com/
 * 2. Crea un nuevo proyecto o selecciona uno existente
 * 3. Habilita "Google+ API"
 * 4. Ve a "Credenciales" → "Crear credenciales" → "ID de cliente de OAuth 2.0"
 * 5. Tipo de aplicación: "Aplicación web"
 * 6. URIs de redireccionamiento autorizados:
 *    - http://localhost/factapex/public/auth/google/callback.php
 * 7. Copia tus credenciales CLIENT_ID y CLIENT_SECRET en el archivo .env
 */

// Cargar variables de entorno
require_once __DIR__ . '/env.php';

// Configuración de Google OAuth
// IMPORTANTE: Por seguridad, las credenciales deben estar en variables de entorno
// o en un archivo .env que NO se suba a GitHub
define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: '');
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: '');

// URI de redirección
define('GOOGLE_REDIRECT_URI', getenv('GOOGLE_REDIRECT_URI') ?: 'http://localhost/factapex/public/auth/google/callback.php');

// Scopes necesarios
define('GOOGLE_SCOPES', [
    'https://www.googleapis.com/auth/userinfo.profile',
    'https://www.googleapis.com/auth/userinfo.email'
]);

// Estados de usuario
define('USER_STATUS_ACTIVE', 'activo');
define('USER_STATUS_PENDING', 'pendiente');
define('USER_STATUS_BLOCKED', 'bloqueado');

// Roles permitidos para OAuth
define('OAUTH_ALLOWED_ROLES', ['cliente']);

// Roles que SOLO pueden usar login local
define('LOCAL_ONLY_ROLES', ['admin', 'ejecutivo']);

/**
 * Verificar si la configuración de Google está completa
 */
function isGoogleOAuthConfigured() {
    return GOOGLE_CLIENT_ID !== 'TU_GOOGLE_CLIENT_ID_AQUI' 
        && GOOGLE_CLIENT_SECRET !== 'TU_GOOGLE_CLIENT_SECRET_AQUI'
        && !empty(GOOGLE_CLIENT_ID) 
        && !empty(GOOGLE_CLIENT_SECRET);
}

/**
 * Realizar petición HTTP usando cURL
 * (Sin dependencias de Composer)
 */
function makeHttpRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Para localhost
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
    }
    
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    if ($error) {
        throw new Exception("cURL Error: $error");
    }
    
    return [
        'code' => $httpCode,
        'body' => $response,
        'data' => json_decode($response, true)
    ];
}

/**
 * Obtener la URL de redirección correcta según el entorno
 */
function getGoogleRedirectUri() {
    // Detectar automáticamente la URL base solo si estamos en contexto web
    if (php_sapi_name() !== 'cli' && isset($_SERVER['HTTP_HOST'])) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        
        // Limpiar script name
        $scriptName = str_replace('\\', '/', $scriptName);
        $scriptName = trim($scriptName, '/');
        
        if (!empty($scriptName)) {
            return "{$protocol}://{$host}/{$scriptName}/auth/google/callback";
        }
        
        return "{$protocol}://{$host}/auth/google/callback";
    }
    
    // En CLI o sin HTTP_HOST, retornar la configurada manualmente
    return GOOGLE_REDIRECT_URI;
}

// Sobrescribir redirect URI con detección automática
if (!defined('GOOGLE_REDIRECT_URI_AUTO')) {
    define('GOOGLE_REDIRECT_URI_AUTO', getGoogleRedirectUri());
}
