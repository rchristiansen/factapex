# 🎯 Sistema de 3 Roles: Admin, Ejecutivo, Cliente

## 📦 Archivos de Implementación

Tu sistema ahora incluye soporte completo para 3 roles. Aquí están todos los archivos creados/modificados:

### 📁 Documentación

| Archivo | Descripción |
|---------|-------------|
| `docs/GUIA_RAPIDA_ROLES.md` | ⚡ **EMPIEZA AQUÍ** - Guía rápida de 5 minutos |
| `docs/IMPLEMENTACION_ROLES.md` | 📚 Documentación completa y detallada |
| `docs/EJEMPLOS_CODIGO_ROLES.php` | 💻 12 ejemplos de código copy-paste |

### 🗄️ Base de Datos

| Archivo | Descripción |
|---------|-------------|
| `database/migrations/add_ejecutivo_role.sql` | Script SQL para agregar rol ejecutivo |

### 🎨 Vistas

| Archivo | Descripción |
|---------|-------------|
| `views/dashboard/admin.php` | Dashboard administrador (modificado) |
| `views/dashboard/ejecutivo.php` | **NUEVO** Dashboard ejecutivo |
| `views/dashboard/cliente.php` | Dashboard cliente (sin cambios) |
| `views/layouts/main.php` | Layout con menú dinámico (modificado) |

### 🎮 Controladores

| Archivo | Descripción |
|---------|-------------|
| `src/Controllers/DashboardController.php` | Con soporte para 3 roles (modificado) |
| `src/Controllers/EjecutivosController.php` | **NUEVO** Gestión de ejecutivos |

### 🗃️ Modelos

| Archivo | Descripción |
|---------|-------------|
| `src/Models/User.php` | Con métodos para roles (modificado) |

### 🛡️ Middleware

| Archivo | Descripción |
|---------|-------------|
| `src/Middleware/RoleMiddleware.php` | **NUEVO** Validación de permisos |

### ⚙️ Configuración

| Archivo | Descripción |
|---------|-------------|
| `config/routes.php` | Rutas con protección de roles (modificado) |

### 🔧 Scripts

| Archivo | Descripción |
|---------|-------------|
| `scripts/crear_ejecutivo.php` | Script helper para crear ejecutivos |

---

## 🚀 Inicio Rápido (3 Pasos)

### 1️⃣ Ejecutar SQL

```bash
# Opción A: Desde phpMyAdmin
# Abre phpMyAdmin → Selecciona tu BD → SQL → Pega el contenido de:
# database/migrations/add_ejecutivo_role.sql

# Opción B: Desde línea de comandos
mysql -u root -p factapex < database/migrations/add_ejecutivo_role.sql
```

### 2️⃣ Probar Login

```
URL: http://localhost/factapex/public/login

Usuarios de prueba:
├─ admin@factapex.com / password      → Administrador
├─ ejecutivo@factapex.com / password  → Ejecutivo
└─ client@factapex.com / password     → Cliente
```

### 3️⃣ Crear tu Primer Ejecutivo

```bash
# Opción A: Desde terminal
php scripts/crear_ejecutivo.php

# Opción B: Desde admin panel
# Login como admin → /ejecutivos → "Nuevo Ejecutivo"
```

---

## 📊 Comparación de Roles

| Característica | Admin | Ejecutivo | Cliente |
|----------------|:-----:|:---------:|:-------:|
| Ver dashboard | ✓ | ✓ | ✓ |
| Gestionar facturas | ✓ | ✓ | Solo propias |
| Crear ejecutivos | ✓ | ✗ | ✗ |
| Ver todos los clientes | ✓ | Solo asignados | ✗ |
| Ver reportes | ✓ | ✓ | ✗ |
| Cuestionario de riesgo | ✗ | ✗ | ✓ |
| Configuración sistema | ✓ | ✗ | ✗ |

---

## 🎯 Casos de Uso Comunes

### ¿Cómo crear un ejecutivo?

**Ver:** `docs/GUIA_RAPIDA_ROLES.md` → Sección "Crear Ejecutivo"

4 formas diferentes explicadas paso a paso.

### ¿Cómo proteger una ruta?

**Ver:** `docs/EJEMPLOS_CODIGO_ROLES.php` → Ejemplo 2 y 9

```php
use Factapex\Middleware\RoleMiddleware;

public function funcionSoloAdmin() {
    RoleMiddleware::checkRole(['admin']);
    // Tu código aquí
}
```

### ¿Cómo mostrar contenido según rol?

**Ver:** `docs/EJEMPLOS_CODIGO_ROLES.php` → Ejemplo 3 y 12

```php
<?php if (RoleMiddleware::isAdmin()): ?>
    <!-- Solo admin ve esto -->
<?php endif; ?>
```

### ¿Cómo filtrar datos según rol?

**Ver:** `docs/EJEMPLOS_CODIGO_ROLES.php` → Ejemplo 6

```php
switch ($_SESSION['user_role']) {
    case 'admin':
        // Ver todo
        break;
    case 'ejecutivo':
        // Ver solo clientes asignados
        break;
    case 'cliente':
        // Ver solo lo propio
        break;
}
```

---

## 🔧 Estructura del Código

### Sistema de Permisos

```
RoleMiddleware
├── checkRole(['admin'])              → Requiere ser admin
├── checkRole(['admin', 'ejecutivo']) → Requiere ser admin O ejecutivo
├── isAdmin()                         → ¿Es admin?
├── isEjecutivo()                     → ¿Es ejecutivo?
└── isCliente()                       → ¿Es cliente?
```

### Flujo de Login

```
1. Usuario ingresa credenciales
2. AuthController::authenticate()
3. Verifica password
4. Establece $_SESSION['user_role']
5. Redirige a /dashboard
6. DashboardController lee el rol
7. Carga vista según rol:
   ├─ admin → dashboard/admin.php
   ├─ ejecutivo → dashboard/ejecutivo.php
   └─ cliente → dashboard/cliente.php
```

---

## 🎨 Personalización del Sidebar

El menú se adapta automáticamente al rol del usuario:

**Admin ve:**
- Dashboard
- Facturas
- **Ejecutivos** ← Exclusivo
- Clientes
- Reportes
- Documentos
- Agenda

**Ejecutivo ve:**
- Dashboard
- Facturas
- Clientes (solo asignados)
- Reportes
- Documentos
- Agenda

**Cliente ve:**
- Dashboard
- Facturas (solo propias)
- **Cuestionario de Riesgo** ← Exclusivo
- Documentos
- Agenda

---

## ✅ Checklist de Verificación

- [ ] SQL ejecutado correctamente
- [ ] Puedo hacer login con `ejecutivo@factapex.com`
- [ ] Como admin, veo la opción "Ejecutivos" en el menú
- [ ] Como ejecutivo, NO veo la opción "Ejecutivos"
- [ ] Cada rol ve su dashboard correcto
- [ ] Puedo crear un nuevo ejecutivo desde admin panel
- [ ] El nuevo ejecutivo puede hacer login

---

## 🐛 Solución de Problemas

### No puedo hacer login como ejecutivo

```sql
-- Verifica que existe:
SELECT * FROM users WHERE email = 'ejecutivo@factapex.com';

-- Si no existe, ejecuta el SQL de nuevo:
SOURCE database/migrations/add_ejecutivo_role.sql;
```

### El menú no se adapta al rol

```php
// Verifica la sesión:
var_dump($_SESSION['user_role']); // Debe mostrar 'admin', 'ejecutivo', o 'cliente'

// Si está mal, cierra sesión y vuelve a entrar
session_destroy();
```

### Error al crear ejecutivo

```php
// Verifica que eres admin:
echo $_SESSION['user_role']; // Debe ser 'admin'

// Solo admin puede crear ejecutivos
```

---

## 📚 Documentación Adicional

| Documento | Cuándo Leer |
|-----------|-------------|
| `GUIA_RAPIDA_ROLES.md` | Primero - Setup inicial |
| `IMPLEMENTACION_ROLES.md` | Después - Entender el sistema |
| `EJEMPLOS_CODIGO_ROLES.php` | Cuando codees - Referencia rápida |

---

## 🔐 Seguridad

✅ **Implementado:**
- Contraseñas hasheadas con `password_hash()`
- Validación de roles en backend
- Protección de rutas según permisos
- Sanitización de inputs
- Verificación de sesión

⚠️ **Recomendado agregar:**
- Rate limiting en login
- Logs de actividad por usuario
- Token CSRF en formularios
- Validación de email único
- Política de contraseñas fuertes

---

## 📞 Soporte

1. **Lee primero:** `docs/GUIA_RAPIDA_ROLES.md`
2. **Busca tu caso:** `docs/EJEMPLOS_CODIGO_ROLES.php`
3. **Revisa detalles:** `docs/IMPLEMENTACION_ROLES.md`
4. **Verifica SQL:** `database/migrations/add_ejecutivo_role.sql`

---

## 🎉 ¡Listo!

Tu sistema ahora soporta 3 roles de forma profesional y escalable:

✅ Admin - Control total
✅ Ejecutivo - Gestión de operaciones
✅ Cliente - Autogestión

**Compatibilidad:** 100% con código anterior
**Testing:** 3 usuarios de prueba listos
**Documentación:** Completa con ejemplos

---

**Versión:** 1.0.0  
**Fecha:** 23/11/2025  
**Desarrollado por:** GitHub Copilot
