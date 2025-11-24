# Guía de Implementación: Sistema de Roles con Ejecutivo

## 📋 Resumen

Esta guía detalla la implementación del tercer rol "ejecutivo" en el sistema Factapex, manteniendo compatibilidad completa con los roles existentes (admin y cliente).

## 🎯 Características Implementadas

### 1. Tres Roles del Sistema

- **Admin**: Acceso completo al sistema, puede crear ejecutivos y gestionar todo
- **Ejecutivo**: Puede ver clientes asignados, gestionar facturas, ver reportes
- **Cliente**: Acceso limitado a sus propias facturas y documentos

## 📦 Archivos Creados/Modificados

### Nuevos Archivos

1. **database/migrations/add_ejecutivo_role.sql**
   - Script SQL para agregar el rol ejecutivo
   - Incluye usuario ejecutivo de prueba

2. **views/dashboard/ejecutivo.php**
   - Dashboard específico para ejecutivos
   - Con skeleton loading

3. **src/Controllers/EjecutivosController.php**
   - Controlador para gestión de ejecutivos (solo admin)
   - Métodos: index, create, store, list, delete

4. **src/Middleware/RoleMiddleware.php**
   - Middleware para validación de permisos por rol
   - Protección de rutas según rol

5. **docs/IMPLEMENTACION_ROLES.md**
   - Esta documentación

### Archivos Modificados

1. **src/Controllers/DashboardController.php**
   - Agregado soporte para rol ejecutivo
   - Método getEjecutivoStats()
   - Switch para redirección según rol

2. **src/Models/User.php**
   - Agregado soporte para rol en create()
   - Método findByRole()
   - Método delete()

3. **config/routes.php**
   - Rutas para gestión de ejecutivos
   - Import de EjecutivosController

4. **views/layouts/main.php**
   - Menú dinámico según rol
   - Opción "Ejecutivos" solo para admin
   - Opciones "Clientes" y "Reportes" para admin y ejecutivo

## 🚀 Pasos de Implementación

### Paso 1: Ejecutar Script SQL

```bash
# Conéctate a MySQL
mysql -u root -p factapex

# O desde phpMyAdmin, ejecuta el contenido de:
# database/migrations/add_ejecutivo_role.sql
```

El script realiza:
```sql
-- Modifica el ENUM para incluir 'ejecutivo'
ALTER TABLE `users` 
MODIFY COLUMN `role` ENUM('admin', 'ejecutivo', 'cliente');

-- Crea usuario ejecutivo de prueba
INSERT INTO `users` (name, email, password, role) 
VALUES ('Ejecutivo Demo', 'ejecutivo@factapex.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ejecutivo');
```

### Paso 2: Verificar Usuarios de Prueba

Ahora tienes 3 usuarios para probar:

| Email | Password | Rol | Acceso |
|-------|----------|-----|--------|
| admin@factapex.com | password | admin | Acceso completo + gestión de ejecutivos |
| ejecutivo@factapex.com | password | ejecutivo | Clientes, facturas, reportes |
| client@factapex.com | password | cliente | Dashboard cliente, facturas propias |

### Paso 3: Probar el Sistema

1. **Como Admin:**
   ```
   - Login: admin@factapex.com
   - Ir a /ejecutivos
   - Crear un nuevo ejecutivo
   - Ver dashboard admin
   ```

2. **Como Ejecutivo:**
   ```
   - Login: ejecutivo@factapex.com
   - Ver dashboard ejecutivo
   - Acceder a clientes y reportes
   - No puede acceder a /ejecutivos (redirige a dashboard)
   ```

3. **Como Cliente:**
   ```
   - Login: client@factapex.com
   - Ver dashboard cliente
   - Solo puede ver sus facturas
   - No puede acceder a clientes ni ejecutivos
   ```

## 🔐 Sistema de Permisos

### Rutas por Rol

```php
// Solo Admin
'/ejecutivos'       → Gestión de ejecutivos
'/configuracion'    → Configuración del sistema
'/usuarios'         → Gestión de usuarios

// Admin + Ejecutivo
'/clientes'         → Gestión de clientes
'/reportes'         → Ver reportes y métricas

// Todos los autenticados
'/dashboard'        → Dashboard según rol
'/facturas'         → Gestión de facturas
'/documentos'       → Gestión de documentos
'/agenda'           → Agenda y recordatorios

// Solo Cliente
'/riesgo'           → Cuestionario de riesgo
```

### Uso del Middleware

En tus controladores, usa:

```php
use Factapex\Middleware\RoleMiddleware;

// Verificar rol específico
public function index() {
    RoleMiddleware::checkRole(['admin']);
    // Solo admin puede continuar
}

// Múltiples roles
public function clientes() {
    RoleMiddleware::checkRole(['admin', 'ejecutivo']);
    // Admin o ejecutivo pueden continuar
}

// Verificaciones individuales
if (RoleMiddleware::isAdmin()) {
    // Código solo para admin
}

if (RoleMiddleware::isEjecutivo()) {
    // Código solo para ejecutivo
}
```

## 📝 Ejemplos de Uso

### Crear un Ejecutivo desde Admin

#### Método 1: Desde el Panel Web

1. Login como admin
2. Ir a `/ejecutivos`
3. Click en "Nuevo Ejecutivo"
4. Llenar formulario:
   - Nombre: Juan Pérez
   - Email: juan@factapex.com
   - Contraseña: (mínimo 6 caracteres)
5. Submit → Se crea el ejecutivo

#### Método 2: Por API (AJAX)

```javascript
fetch(window.PUBLIC_PATH + '/ejecutivos/store', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: new URLSearchParams({
        name: 'Juan Pérez',
        email: 'juan@factapex.com',
        password: 'mipassword123'
    })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        alert('Ejecutivo creado exitosamente');
    }
});
```

#### Método 3: Script PHP Directo

```php
<?php
require_once __DIR__ . '/../config/app.php';

use Factapex\Models\User;

$userModel = new User();

$ejecutivoData = [
    'name' => 'María García',
    'email' => 'maria@factapex.com',
    'password' => password_hash('password123', PASSWORD_DEFAULT),
    'role' => 'ejecutivo'
];

$userId = $userModel->create($ejecutivoData);

if ($userId) {
    echo "Ejecutivo creado con ID: $userId\n";
} else {
    echo "Error al crear ejecutivo\n";
}
```

### Listar Ejecutivos

```php
use Factapex\Models\User;

$userModel = new User();
$ejecutivos = $userModel->findByRole('ejecutivo');

foreach ($ejecutivos as $ejecutivo) {
    echo "ID: {$ejecutivo['id']} - {$ejecutivo['name']} ({$ejecutivo['email']})\n";
}
```

### Verificar Rol en la Sesión

```php
// En cualquier parte del código después del login
$role = $_SESSION['user_role'] ?? 'cliente';

switch ($role) {
    case 'admin':
        // Lógica para admin
        break;
    case 'ejecutivo':
        // Lógica para ejecutivo
        break;
    case 'cliente':
        // Lógica para cliente
        break;
}
```

## 🎨 Estructura del Sidebar por Rol

### Admin ve:
```
├── Dashboard
├── Facturas
├── Ejecutivos      ← Solo admin
├── Clientes
├── Reportes
├── Documentos
└── Agenda
```

### Ejecutivo ve:
```
├── Dashboard
├── Facturas
├── Clientes
├── Reportes
├── Documentos
└── Agenda
```

### Cliente ve:
```
├── Dashboard
├── Facturas
├── Cuestionario de Riesgo  ← Solo cliente
├── Documentos
└── Agenda
```

## 🔄 Flujo de Login por Rol

```
Login
  ↓
AuthController::authenticate()
  ↓
Verifica credenciales
  ↓
Establece $_SESSION['user_role']
  ↓
Redirige a /dashboard
  ↓
DashboardController::index()
  ↓
Switch según rol:
  - admin → dashboard/admin.php
  - ejecutivo → dashboard/ejecutivo.php
  - cliente → dashboard/cliente.php
```

## 🛡️ Compatibilidad con Sistema Anterior

✅ **100% Compatible**

- Los usuarios admin existentes siguen funcionando
- Los clientes existentes siguen funcionando
- No se requieren cambios en código existente
- Solo se agregan nuevas funcionalidades

### Migración Segura

El script SQL usa `ALTER TABLE` para modificar el ENUM, lo que:
- ✅ Mantiene todos los datos existentes
- ✅ No afecta a usuarios actuales
- ✅ Es reversible si es necesario

## 📊 Estadísticas por Rol

### Admin Stats
```php
[
    'clientes_totales' => 45,
    'facturas_totales' => 234,
    'pendientes_aprobacion' => 18,
    'volumen_total' => 1200000
]
```

### Ejecutivo Stats
```php
[
    'clientes_asignados' => 12,
    'facturas_gestionadas' => 45,
    'en_proceso' => 8,
    'volumen_gestionado' => 450000
]
```

### Cliente Stats
```php
[
    'facturas_totales' => 12,
    'en_revision' => 3,
    'aprobadas' => 9,
    'monto_total' => 45000
]
```

## 🐛 Troubleshooting

### Problema: "Rol no reconocido"

**Solución:** Verifica que ejecutaste el script SQL correctamente

```sql
SHOW COLUMNS FROM users WHERE Field = 'role';
-- Debe mostrar: enum('admin','ejecutivo','cliente')
```

### Problema: "No puedo acceder a /ejecutivos"

**Solución:** Verifica tu rol en la sesión

```php
echo $_SESSION['user_role']; // Debe ser 'admin'
```

### Problema: "El menú no muestra opciones correctas"

**Solución:** Limpia la sesión y vuelve a hacer login

```php
session_destroy();
// Logout y login de nuevo
```

## 📝 Notas Adicionales

### Seguridad

- Todas las contraseñas se hashean con `password_hash()`
- Validación de roles en backend (no solo frontend)
- Protección CSRF en formularios
- Sanitización de inputs

### Performance

- Dashboard usa skeleton loading (800ms)
- Carga asíncrona de datos
- Queries optimizadas por rol

### Escalabilidad

- Fácil agregar nuevos roles
- Permisos centralizados en RoleMiddleware
- Estructura modular

## 🎯 Próximos Pasos Recomendados

1. Implementar asignación de clientes a ejecutivos
2. Crear sistema de notificaciones por rol
3. Agregar reportes específicos para ejecutivos
4. Implementar logs de actividad por usuario
5. Agregar gestión de permisos granulares

## 📞 Soporte

Si encuentras problemas:
1. Verifica que ejecutaste el script SQL
2. Revisa los logs en `storage/logs/`
3. Verifica la sesión con `var_dump($_SESSION)`
4. Limpia caché del navegador

---

**Versión:** 1.0.0  
**Fecha:** 23/11/2025  
**Autor:** GitHub Copilot
