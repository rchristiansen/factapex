<?php
/**
 * Ejemplos de código PHP para el sistema de 3 roles
 * Copia y pega según necesites
 */

// ============================================
// EJEMPLO 1: Verificar rol en la sesión
// ============================================

// Obtener el rol actual
$role = $_SESSION['user_role'] ?? 'cliente';

// Switch básico
switch ($role) {
    case 'admin':
        echo "Bienvenido Administrador";
        // Código específico para admin
        break;
    
    case 'ejecutivo':
        echo "Bienvenido Ejecutivo";
        // Código específico para ejecutivo
        break;
    
    case 'cliente':
        echo "Bienvenido Cliente";
        // Código específico para cliente
        break;
}

// ============================================
// EJEMPLO 2: Proteger una función/método
// ============================================

use Factapex\Middleware\RoleMiddleware;

function funcionSoloAdmin() {
    // Solo admin puede ejecutar esta función
    RoleMiddleware::checkRole(['admin']);
    
    // Tu código aquí...
    echo "Esta función solo la ejecuta admin";
}

function funcionAdminYEjecutivo() {
    // Admin o ejecutivo pueden ejecutar
    RoleMiddleware::checkRole(['admin', 'ejecutivo']);
    
    // Tu código aquí...
    echo "Admin o ejecutivo pueden ver esto";
}

// ============================================
// EJEMPLO 3: Mostrar contenido según rol
// ============================================

?>
<div class="panel">
    <?php if (RoleMiddleware::isAdmin()): ?>
        <!-- Solo admin ve esto -->
        <button onclick="crearEjecutivo()">Crear Ejecutivo</button>
        <button onclick="eliminarUsuario()">Eliminar Usuario</button>
    <?php endif; ?>
    
    <?php if (RoleMiddleware::isAdmin() || RoleMiddleware::isEjecutivo()): ?>
        <!-- Admin y ejecutivo ven esto -->
        <button onclick="verClientes()">Ver Clientes</button>
        <button onclick="generarReporte()">Generar Reporte</button>
    <?php endif; ?>
    
    <?php if (RoleMiddleware::isCliente()): ?>
        <!-- Solo cliente ve esto -->
        <button onclick="misFacturas()">Mis Facturas</button>
        <button onclick="cuestionarioRiesgo()">Cuestionario de Riesgo</button>
    <?php endif; ?>
</div>

<?php
// ============================================
// EJEMPLO 4: Crear usuario con rol específico
// ============================================

use Factapex\Models\User;

function crearUsuarioConRol($nombre, $email, $password, $rol = 'cliente') {
    // Validar que el rol sea válido
    $rolesValidos = ['admin', 'ejecutivo', 'cliente'];
    if (!in_array($rol, $rolesValidos)) {
        return [
            'success' => false,
            'message' => 'Rol no válido'
        ];
    }
    
    // Verificar que solo admin pueda crear admins o ejecutivos
    $currentRole = $_SESSION['user_role'] ?? null;
    if (in_array($rol, ['admin', 'ejecutivo']) && $currentRole !== 'admin') {
        return [
            'success' => false,
            'message' => 'Solo admin puede crear usuarios admin o ejecutivos'
        ];
    }
    
    $userModel = new User();
    
    // Verificar email único
    if ($userModel->findByEmail($email)) {
        return [
            'success' => false,
            'message' => 'El email ya existe'
        ];
    }
    
    // Crear usuario
    $userId = $userModel->create([
        'name' => $nombre,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => $rol
    ]);
    
    if ($userId) {
        return [
            'success' => true,
            'message' => 'Usuario creado exitosamente',
            'user_id' => $userId,
            'role' => $rol
        ];
    }
    
    return [
        'success' => false,
        'message' => 'Error al crear usuario'
    ];
}

// Uso:
$resultado = crearUsuarioConRol('Juan Pérez', 'juan@example.com', 'password123', 'ejecutivo');
if ($resultado['success']) {
    echo "Usuario creado con ID: {$resultado['user_id']}";
}

// ============================================
// EJEMPLO 5: Redirección según rol
// ============================================

function redirigirSegunRol() {
    $role = $_SESSION['user_role'] ?? 'cliente';
    
    switch ($role) {
        case 'admin':
            header('Location: ' . PUBLIC_PATH . '/dashboard');
            break;
        case 'ejecutivo':
            header('Location: ' . PUBLIC_PATH . '/dashboard');
            break;
        case 'cliente':
            header('Location: ' . PUBLIC_PATH . '/dashboard');
            break;
        default:
            header('Location: ' . PUBLIC_PATH . '/login');
    }
    exit;
}

// ============================================
// EJEMPLO 6: Filtrar datos según rol
// ============================================

function obtenerFacturasSegunRol($userId) {
    $role = $_SESSION['user_role'] ?? 'cliente';
    
    // Aquí irían tus consultas SQL reales
    switch ($role) {
        case 'admin':
            // Admin ve todas las facturas
            $sql = "SELECT * FROM facturas ORDER BY created_at DESC";
            break;
            
        case 'ejecutivo':
            // Ejecutivo ve facturas de sus clientes asignados
            $sql = "SELECT f.* FROM facturas f 
                    INNER JOIN clientes c ON f.cliente_id = c.id 
                    WHERE c.ejecutivo_id = :user_id 
                    ORDER BY f.created_at DESC";
            break;
            
        case 'cliente':
        default:
            // Cliente solo ve sus facturas
            $sql = "SELECT * FROM facturas 
                    WHERE cliente_id = :user_id 
                    ORDER BY created_at DESC";
            break;
    }
    
    // Ejecutar query y retornar resultados
    // return $results;
}

// ============================================
// EJEMPLO 7: API endpoint con validación de rol
// ============================================

function apiCrearEjecutivo() {
    header('Content-Type: application/json');
    
    // Verificar que sea admin
    $currentRole = $_SESSION['user_role'] ?? null;
    if ($currentRole !== 'admin') {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'No autorizado. Solo administradores pueden crear ejecutivos.'
        ]);
        return;
    }
    
    // Validar datos
    $nombre = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($nombre) || empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Todos los campos son requeridos'
        ]);
        return;
    }
    
    // Crear ejecutivo
    $resultado = crearUsuarioConRol($nombre, $email, $password, 'ejecutivo');
    
    if ($resultado['success']) {
        http_response_code(201);
        echo json_encode($resultado);
    } else {
        http_response_code(400);
        echo json_encode($resultado);
    }
}

// ============================================
// EJEMPLO 8: Menú dinámico en PHP
// ============================================

function generarMenuSegunRol() {
    $role = $_SESSION['user_role'] ?? 'cliente';
    
    // Menú base para todos
    $menu = [
        ['url' => '/dashboard', 'nombre' => 'Dashboard', 'icono' => 'home'],
        ['url' => '/facturas', 'nombre' => 'Facturas', 'icono' => 'document'],
    ];
    
    // Agregar opciones según rol
    if ($role === 'admin') {
        $menu[] = ['url' => '/ejecutivos', 'nombre' => 'Ejecutivos', 'icono' => 'users'];
        $menu[] = ['url' => '/clientes', 'nombre' => 'Clientes', 'icono' => 'people'];
        $menu[] = ['url' => '/reportes', 'nombre' => 'Reportes', 'icono' => 'chart'];
    }
    
    if ($role === 'ejecutivo') {
        $menu[] = ['url' => '/clientes', 'nombre' => 'Mis Clientes', 'icono' => 'people'];
        $menu[] = ['url' => '/reportes', 'nombre' => 'Reportes', 'icono' => 'chart'];
    }
    
    if ($role === 'cliente') {
        $menu[] = ['url' => '/riesgo', 'nombre' => 'Cuestionario', 'icono' => 'alert'];
    }
    
    // Menú común para todos
    $menu[] = ['url' => '/documentos', 'nombre' => 'Documentos', 'icono' => 'folder'];
    $menu[] = ['url' => '/agenda', 'nombre' => 'Agenda', 'icono' => 'calendar'];
    
    return $menu;
}

// Uso en vista:
$menu = generarMenuSegunRol();
foreach ($menu as $item) {
    echo "<a href='{$item['url']}'>{$item['nombre']}</a>";
}

// ============================================
// EJEMPLO 9: Validar permisos en controlador
// ============================================

class MiControlador {
    
    public function index() {
        // Método accesible para todos los autenticados
        echo "Todos pueden ver esto";
    }
    
    public function crearEjecutivo() {
        // Solo admin
        RoleMiddleware::checkRole(['admin']);
        
        // Código para crear ejecutivo
        echo "Formulario para crear ejecutivo";
    }
    
    public function verClientes() {
        // Admin o ejecutivo
        RoleMiddleware::checkRole(['admin', 'ejecutivo']);
        
        // Código para ver clientes
        $role = $_SESSION['user_role'];
        if ($role === 'admin') {
            // Admin ve todos los clientes
            $clientes = $this->obtenerTodosLosClientes();
        } else {
            // Ejecutivo solo ve sus clientes
            $ejecutivoId = $_SESSION['user_id'];
            $clientes = $this->obtenerClientesDeEjecutivo($ejecutivoId);
        }
        
        return $clientes;
    }
    
    private function obtenerTodosLosClientes() {
        // Query para obtener todos
        return [];
    }
    
    private function obtenerClientesDeEjecutivo($ejecutivoId) {
        // Query filtrada por ejecutivo
        return [];
    }
}

// ============================================
// EJEMPLO 10: Login con redirección automática
// ============================================

function procesarLogin($email, $password) {
    $userModel = new User();
    $user = $userModel->findByEmail($email);
    
    if ($user && password_verify($password, $user['password'])) {
        // Login exitoso
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        
        // Redirigir según rol
        $redirectUrl = PUBLIC_PATH . '/dashboard';
        
        return [
            'success' => true,
            'message' => 'Login exitoso',
            'redirect' => $redirectUrl,
            'role' => $user['role']
        ];
    }
    
    return [
        'success' => false,
        'message' => 'Credenciales incorrectas'
    ];
}

// ============================================
// EJEMPLO 11: Listar usuarios por rol
// ============================================

function listarUsuariosPorRol($rol) {
    // Solo admin puede listar usuarios
    RoleMiddleware::checkRole(['admin']);
    
    $userModel = new User();
    $usuarios = $userModel->findByRole($rol);
    
    return $usuarios;
}

// Uso:
$ejecutivos = listarUsuariosPorRol('ejecutivo');
$clientes = listarUsuariosPorRol('cliente');
$admins = listarUsuariosPorRol('admin');

// ============================================
// EJEMPLO 12: Verificación de permisos en vistas
// ============================================
?>

<!-- En tus archivos .php de vistas -->

<?php if ($_SESSION['user_role'] === 'admin'): ?>
    <div class="admin-panel">
        <h2>Panel de Administración</h2>
        <button onclick="crearEjecutivo()">Crear Ejecutivo</button>
    </div>
<?php endif; ?>

<?php if (in_array($_SESSION['user_role'], ['admin', 'ejecutivo'])): ?>
    <div class="reportes-panel">
        <h2>Reportes</h2>
        <button onclick="generarReporte()">Generar Reporte</button>
    </div>
<?php endif; ?>

<?php
// ============================================
// RESUMEN DE FUNCIONES ÚTILES
// ============================================

/*
RoleMiddleware::checkRole(['admin']);                    // Solo admin
RoleMiddleware::checkRole(['admin', 'ejecutivo']);       // Admin o ejecutivo
RoleMiddleware::isAdmin();                               // true si es admin
RoleMiddleware::isEjecutivo();                           // true si es ejecutivo
RoleMiddleware::isCliente();                             // true si es cliente

$_SESSION['user_role']                                   // 'admin', 'ejecutivo', o 'cliente'
$_SESSION['user_id']                                     // ID del usuario
$_SESSION['user_name']                                   // Nombre del usuario
$_SESSION['user_email']                                  // Email del usuario
*/
