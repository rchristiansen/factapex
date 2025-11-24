<?php

namespace Factapex\Services;

use Factapex\Models\User;
use Exception;

/**
 * Servicio para manejar autenticación con Google OAuth
 * Solo permite login/registro de CLIENTES
 * 
 * SIN COMPOSER - Usa cURL directo para comunicarse con Google
 */
class GoogleOAuthService {
    
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $userModel;
    
    // URLs de Google OAuth
    const OAUTH_AUTHORIZE_URL = 'https://accounts.google.com/o/oauth2/v2/auth';
    const OAUTH_TOKEN_URL = 'https://oauth2.googleapis.com/token';
    const OAUTH_USERINFO_URL = 'https://www.googleapis.com/oauth2/v2/userinfo';
    
    public function __construct() {
        require_once __DIR__ . '/../../config/google_oauth.php';
        
        if (!isGoogleOAuthConfigured()) {
            throw new Exception('Google OAuth no está configurado. Revisa config/google_oauth.php');
        }
        
        $this->clientId = GOOGLE_CLIENT_ID;
        $this->clientSecret = GOOGLE_CLIENT_SECRET;
        $this->redirectUri = GOOGLE_REDIRECT_URI;
        
        $this->userModel = new User();
    }
    
    /**
     * Obtener la URL de autenticación de Google
     */
    public function getAuthUrl() {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'online'
        ];
        
        return self::OAUTH_AUTHORIZE_URL . '?' . http_build_query($params);
    }
    
    /**
     * Procesar el callback de Google y autenticar/crear usuario
     * 
     * @param string $code Código de autorización de Google
     * @return array ['success' => bool, 'user' => array|null, 'message' => string]
     */
    public function handleCallback($code) {
        try {
            // 1. Intercambiar código por token de acceso
            $tokenData = [
                'code' => $code,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUri,
                'grant_type' => 'authorization_code'
            ];
            
            $tokenResponse = $this->makePostRequest(self::OAUTH_TOKEN_URL, $tokenData);
            
            if (!isset($tokenResponse['access_token'])) {
                return [
                    'success' => false,
                    'message' => 'Error al obtener token de Google: ' . ($tokenResponse['error'] ?? 'Unknown error')
                ];
            }
            
            $accessToken = $tokenResponse['access_token'];
            
            // 2. Obtener información del usuario
            $googleUser = $this->getUserInfo($accessToken);
            
            if (!$googleUser || empty($googleUser['email'])) {
                return [
                    'success' => false,
                    'message' => 'No se pudo obtener el email de Google'
                ];
            }
            
            // 3. Buscar usuario existente por email
            $existingUser = $this->userModel->findByEmail($googleUser['email']);
            
            if ($existingUser) {
                // Usuario existe - Validar que puede usar OAuth
                return $this->handleExistingUser($existingUser, $googleUser);
            } else {
                // Usuario nuevo - Crear como cliente
                return $this->createNewGoogleUser($googleUser);
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error en autenticación con Google: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Hacer petición POST usando cURL
     */
    private function makePostRequest($url, $data) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Para localhost
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            throw new Exception("cURL Error: $error");
        }
        
        if ($httpCode !== 200) {
            throw new Exception("HTTP Error: $httpCode");
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Obtener información del usuario de Google
     */
    private function getUserInfo($accessToken) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, self::OAUTH_USERINFO_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken
        ]);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            throw new Exception("cURL Error: $error");
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Manejar usuario existente
     */
    private function handleExistingUser($existingUser, $googleUser) {
        // REGLA: Admin y ejecutivo NO pueden usar Google OAuth
        if (in_array($existingUser['role'], LOCAL_ONLY_ROLES)) {
            return [
                'success' => false,
                'message' => 'Este email pertenece a una cuenta interna. Por favor use el login tradicional.',
                'blocked' => true
            ];
        }
        
        // REGLA: Si el usuario es local (password) pero rol cliente, permitir
        if ($existingUser['provider'] === 'local' && $existingUser['role'] === 'cliente') {
            // Actualizar a Google provider y vincular cuenta
            $this->userModel->update($existingUser['id'], [
                'provider' => 'google',
                'provider_id' => $googleUser['id'],
                'avatar' => $googleUser['picture'] ?? null,
                'email_verified' => isset($googleUser['verified_email']) && $googleUser['verified_email'] ? 1 : 0,
                'last_login' => date('Y-m-d H:i:s')
            ]);
            
            $existingUser['provider'] = 'google';
            $existingUser['provider_id'] = $googleUser['id'];
        }
        
        // REGLA: Si ya es Google user, solo actualizar last_login
        if ($existingUser['provider'] === 'google') {
            $this->userModel->update($existingUser['id'], [
                'last_login' => date('Y-m-d H:i:s')
            ]);
        }
        
        return [
            'success' => true,
            'user' => $existingUser,
            'message' => 'Login exitoso'
        ];
    }
    
    /**
     * Crear nuevo usuario desde Google (siempre como CLIENTE)
     * Ahora requiere completar perfil primero
     */
    private function createNewGoogleUser($googleUser) {
        // Usuario nuevo debe completar perfil
        return [
            'success' => true,
            'needs_profile' => true,
            'google_data' => $googleUser,
            'message' => 'Completa tu perfil para continuar'
        ];
    }
    
    /**
     * Crear usuario después de completar perfil
     */
    public function createUserWithProfile($googleData, $profileData) {
        try {
            // Validar que las contraseñas coincidan
            if ($profileData['password'] !== $profileData['password_confirm']) {
                return [
                    'success' => false,
                    'message' => 'Las contraseñas no coinciden'
                ];
            }
            
            // Validar longitud de contraseña
            if (strlen($profileData['password']) < 6) {
                return [
                    'success' => false,
                    'message' => 'La contraseña debe tener al menos 6 caracteres'
                ];
            }
            
            $userId = $this->userModel->create([
                'name' => trim($profileData['nombre'] . ' ' . $profileData['apellido']),
                'email' => $googleData['email'],
                'password' => password_hash($profileData['password'], PASSWORD_DEFAULT),
                'role' => 'cliente', // SIEMPRE cliente
                'provider' => 'google',
                'provider_id' => $googleData['id'],
                'estado' => USER_STATUS_ACTIVE, // Activo porque viene de Google verificado
                'avatar' => $googleData['picture'] ?? null,
                'email_verified' => isset($googleData['verified_email']) && $googleData['verified_email'] ? 1 : 0
            ]);
            
            if (!$userId) {
                return [
                    'success' => false,
                    'message' => 'Error al crear usuario en la base de datos'
                ];
            }
            
            // Obtener el usuario recién creado
            $newUser = $this->userModel->findById($userId);
            
            return [
                'success' => true,
                'user' => $newUser,
                'message' => 'Cuenta creada exitosamente',
                'is_new' => true
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al crear usuario: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Verificar si un usuario puede usar Google OAuth
     */
    public static function canUseGoogleOAuth($user) {
        if (!$user) {
            return true; // Usuario nuevo puede registrarse
        }
        
        // Admin y ejecutivo NO pueden
        if (in_array($user['role'], LOCAL_ONLY_ROLES)) {
            return false;
        }
        
        return true;
    }
}
