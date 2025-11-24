# ⚡ Guía Rápida: Sistema de 3 Roles

## 🎯 Los 3 Roles

| Rol | Acceso | Login de Prueba |
|-----|--------|----------------|
| **admin** | Todo el sistema + crear ejecutivos | admin@factapex.com / password |
| **ejecutivo** | Clientes + Facturas + Reportes | ejecutivo@factapex.com / password |
| **cliente** | Dashboard cliente + Facturas propias | client@factapex.com / password |

## 🚀 Instalación (5 minutos)

### 1. Ejecutar SQL
```bash
# Desde phpMyAdmin o consola MySQL:
mysql -u root -p factapex < database/migrations/add_ejecutivo_role.sql
```

### 2. Verificar
```bash
# Ya puedes hacer login con: ejecutivo@factapex.com / password
```

## 📝 Crear Ejecutivo

### Opción A: Desde Admin Panel
1. Login como admin
2. Ir a `/ejecutivos`
3. Click "Nuevo Ejecutivo"
4. Llenar formulario → Submit

### Opción B: Script PHP
```bash
php scripts/crear_ejecutivo.php
```

### Opción C: Código PHP
```php
use Factapex\Models\User;

$userModel = new User();
$userId = $userModel->create([
    'name' => 'Nombre',
    'email' => 'email@factapex.com',
    'password' => password_hash('password', PASSWORD_DEFAULT),
    'role' => 'ejecutivo'
]);
```

### Opción D: API (AJAX)
```javascript
fetch('/ejecutivos/store', {
    method: 'POST',
    body: new URLSearchParams({
        name: 'Nombre',
        email: 'email@factapex.com',
        password: 'password'
    })
});
```

## 🔐 Verificar Rol en Código

```php
// Método 1: Sesión directa
$role = $_SESSION['user_role']; // 'admin', 'ejecutivo', o 'cliente'

// Método 2: Middleware helper
use Factapex\Middleware\RoleMiddleware;

if (RoleMiddleware::isAdmin()) {
    // Código admin
}

if (RoleMiddleware::isEjecutivo()) {
    // Código ejecutivo
}

if (RoleMiddleware::isCliente()) {
    // Código cliente
}

// Método 3: Validar acceso
RoleMiddleware::checkRole(['admin', 'ejecutivo']); // Solo estos pueden continuar
```

## 🎨 Menú por Rol

| Opción | Admin | Ejecutivo | Cliente |
|--------|-------|-----------|---------|
| Dashboard | ✓ | ✓ | ✓ |
| Facturas | ✓ | ✓ | ✓ |
| Ejecutivos | ✓ | ✗ | ✗ |
| Clientes | ✓ | ✓ | ✗ |
| Reportes | ✓ | ✓ | ✗ |
| Cuest. Riesgo | ✗ | ✗ | ✓ |
| Documentos | ✓ | ✓ | ✓ |
| Agenda | ✓ | ✓ | ✓ |

## 🛡️ Proteger Rutas

En `config/routes.php` o controladores:

```php
// Solo admin
RoleMiddleware::checkRole(['admin']);

// Admin o ejecutivo
RoleMiddleware::checkRole(['admin', 'ejecutivo']);

// Todos autenticados (ya protegido por AuthMiddleware)
// No hace falta nada adicional
```

## 📂 Archivos Importantes

```
database/migrations/
  └── add_ejecutivo_role.sql          ← Ejecutar primero

src/Controllers/
  ├── DashboardController.php         ← Modificado (switch por rol)
  └── EjecutivosController.php        ← Nuevo (gestión ejecutivos)

src/Models/
  └── User.php                        ← Modificado (create con role)

src/Middleware/
  └── RoleMiddleware.php              ← Nuevo (validación permisos)

views/dashboard/
  ├── admin.php                       ← Existente
  ├── ejecutivo.php                   ← Nuevo
  └── cliente.php                     ← Existente

views/layouts/
  └── main.php                        ← Modificado (menú dinámico)

docs/
  └── IMPLEMENTACION_ROLES.md         ← Documentación completa

scripts/
  └── crear_ejecutivo.php             ← Script helper
```

## 🐛 Solución Rápida de Problemas

### "No puedo hacer login como ejecutivo"
```sql
-- Verifica que el script SQL se ejecutó:
SELECT * FROM users WHERE email = 'ejecutivo@factapex.com';
```

### "El menú no muestra opciones correctas"
```php
// Limpia sesión y vuelve a hacer login
session_destroy();
```

### "Error al crear ejecutivo"
```php
// Verifica rol en la sesión
echo $_SESSION['user_role']; // Debe ser 'admin'
```

## ✅ Checklist de Implementación

- [ ] Ejecutar `add_ejecutivo_role.sql`
- [ ] Verificar login con `ejecutivo@factapex.com`
- [ ] Login como admin y acceder a `/ejecutivos`
- [ ] Crear un ejecutivo desde el panel
- [ ] Login con el nuevo ejecutivo
- [ ] Verificar que cada rol ve su menú correcto
- [ ] Probar que ejecutivo NO puede acceder a `/ejecutivos`

## 📞 Referencia Rápida

**Usuarios de Prueba:**
```
admin@factapex.com / password      → Admin
ejecutivo@factapex.com / password  → Ejecutivo  
client@factapex.com / password     → Cliente
```

**Crear Ejecutivo:**
```bash
php scripts/crear_ejecutivo.php
```

**Documentación Completa:**
```
docs/IMPLEMENTACION_ROLES.md
```

---

**¿Todo funciona? ✓**
- Sistema 100% compatible con código anterior
- Admin y cliente siguen funcionando igual
- Ejecutivo es el nuevo rol con acceso medio
