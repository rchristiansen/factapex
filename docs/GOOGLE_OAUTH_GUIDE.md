# 🔐 Google OAuth Implementation Guide
# Factapex - PHP 8.2.12 + MySQL 8.0.44

## 📁 Estructura de Archivos Generados

```
factapex/
├── config/
│   └── google_oauth.php              ← Configuración OAuth
│
├── database/
│   ├── migrations/
│   │   ├── update_users_oauth.sql    ← Actualizar tabla existente
│   │   └── create_users_full.sql     ← Tabla completa desde cero
│   └── seeds/
│       └── seed_users.php            ← Usuarios de prueba
│
├── src/
│   ├── Models/
│   │   └── User.php                  ← Actualizado con OAuth
│   ├── Services/
│   │   └── GoogleOAuthService.php    ← Lógica OAuth
│   └── Middleware/
│       ├── protect_admin.php         ← Middleware admin
│       ├── protect_ejecutivo.php     ← Middleware ejecutivo
│       └── protect_cliente.php       ← Middleware cliente
│
├── public/
│   └── auth/
│       └── google/
│           ├── login.php             ← Iniciar OAuth
│           └── callback.php          ← Procesar respuesta
│
├── views/
│   └── auth/
│       └── login_with_google.php     ← UI de login
│
└── composer.json                     ← Dependencias
```

---

## 🚀 Instalación Paso a Paso

### 1️⃣ Instalar Google Client Library

```bash
cd c:\xampp\htdocs\factapex
composer require google/apiclient:"^2.15"
```

### 2️⃣ Actualizar Base de Datos

Opción A - Si ya tienes tabla `users`:
```bash
mysql -u root -p factapex < database/migrations/update_users_oauth.sql
```

Opción B - Crear tabla desde cero:
```bash
mysql -u root -p factapex < database/migrations/create_users_full.sql
```

### 3️⃣ Insertar Usuarios de Prueba

```bash
php database/seeds/seed_users.php
```

Esto creará:
- ✅ Admin: admin@factapex.com / password
- ✅ Ejecutivo: ejecutivo@factapex.com / password
- ✅ Cliente: cliente@factapex.com / password
- ✅ Cliente Google: cliente.google@factapex.com (OAuth)

---

## 🔑 Configurar Google Cloud Console

### Paso 1: Crear Proyecto
1. Ve a https://console.cloud.google.com/
2. Clic en "Nuevo Proyecto"
3. Nombre: `Factapex OAuth`
4. Crear

### Paso 2: Habilitar APIs
1. Menú → APIs y servicios → Biblioteca
2. Buscar "Google+ API" o "People API"
3. Habilitar

### Paso 3: Crear Credenciales OAuth
1. APIs y servicios → Credenciales
2. Crear credenciales → ID de cliente de OAuth 2.0
3. Configurar pantalla de consentimiento:
   - Tipo: Externo
   - Nombre: Factapex
   - Email de soporte: tu@email.com
   - Ámbitos: email, profile
   - Guardar

4. Crear ID de cliente:
   - Tipo: Aplicación web
   - Nombre: Factapex Web Client
   - **Orígenes autorizados**:
     ```
     http://localhost
     http://localhost:80
     ```
   - **URIs de redirección**:
     ```
     http://localhost/factapex/public/auth/google/callback
     http://localhost/factapex/auth/google/callback
     ```
   - Crear

5. **Copiar CLIENT_ID y CLIENT_SECRET**

### Paso 4: Configurar en tu proyecto

Edita `config/google_oauth.php`:

```php
define('GOOGLE_CLIENT_ID', 'TU_CLIENT_ID_AQUI.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'TU_CLIENT_SECRET_AQUI');
```

O usa variables de entorno:
```bash
set GOOGLE_CLIENT_ID=tu_client_id
set GOOGLE_CLIENT_SECRET=tu_secret
```

---

## 📋 Reglas de Negocio Implementadas

### ✅ Clientes
- ✅ Pueden registrarse con Google OAuth
- ✅ Se crean con `role = 'cliente'`
- ✅ Estado inicial: `pendiente`
- ✅ Provider: `google`
- ✅ Pueden vincular cuenta local existente con Google

### ❌ Admin y Ejecutivo
- ❌ NO pueden iniciar sesión con Google
- ❌ NO se crean por Google OAuth
- ❌ Solo login tradicional (email + password)
- ❌ Si intentan OAuth → bloqueado

### 🔐 Validaciones
1. Email existente con rol admin/ejecutivo → **bloqueado**
2. Email existente con rol cliente → **login + vinculación**
3. Email nuevo → **crear cliente pendiente**
4. Provider local con rol != cliente → **no OAuth**

---

## 🎯 Flujos de Usuario

### Flujo 1: Cliente Nuevo con Google

```
Usuario → Clic "Continuar con Google" 
       → Redirige a Google
       → Autoriza app
       → Callback procesa
       → Crea user (role=cliente, estado=pendiente, provider=google)
       → Redirige a /dashboard
```

### Flujo 2: Cliente Existente (local) usa Google

```
Usuario → Ya existe en DB como cliente local
       → Clic "Continuar con Google"
       → Sistema vincula: actualiza provider=google, provider_id=xxx
       → Login exitoso
       → Redirige a /dashboard
```

### Flujo 3: Admin/Ejecutivo intenta Google ❌

```
Admin → Clic "Continuar con Google"
     → Sistema detecta email con role=admin
     → BLOQUEA acceso
     → Mensaje: "Cuenta interna, use login tradicional"
     → Redirige a /login
```

### Flujo 4: Admin/Ejecutivo login tradicional ✅

```
Admin → Form email + password
     → Valida credenciales
     → Verifica provider=local
     → Login exitoso
     → Redirige a /dashboard (admin view)
```

---

## 🧪 Testing

### Test 1: Verificar Base de Datos
```sql
SELECT id, name, email, role, provider, estado 
FROM users 
ORDER BY role;
```

Deberías ver:
```
id | name              | email                    | role      | provider | estado
1  | Admin Principal   | admin@factapex.com       | admin     | local    | activo
2  | Ejecutivo Ventas  | ejecutivo@factapex.com   | ejecutivo | local    | activo
3  | Cliente Demo      | cliente@factapex.com     | cliente   | local    | activo
4  | Cliente Google    | cliente.google@...       | cliente   | google   | pendiente
```

### Test 2: Login Admin
1. Ve a `http://localhost/factapex/public/login`
2. Email: `admin@factapex.com`
3. Password: `password`
4. ✅ Debe redirigir a dashboard admin

### Test 3: Login con Google (Cliente Nuevo)
1. Ve a `http://localhost/factapex/public/login`
2. Clic en "Continuar con Google"
3. Selecciona cuenta Google
4. ✅ Debe crear usuario y redirigir a dashboard cliente
5. Verifica en DB:
   ```sql
   SELECT * FROM users WHERE email = 'tu_email_google@gmail.com';
   ```
   - role = 'cliente'
   - provider = 'google'
   - estado = 'pendiente'

### Test 4: Bloqueo Admin por Google ❌
1. Asegúrate que `admin@factapex.com` existe
2. Intenta login Google con ese email
3. ✅ Debe mostrar: "Cuenta interna, use login tradicional"

---

## 🔧 Configuración de Redirecciones

### Estructura de URLs

Si tu proyecto está en `c:\xampp\htdocs\factapex\`:

```
Login:    http://localhost/factapex/public/auth/google/login
Callback: http://localhost/factapex/public/auth/google/callback
```

### Ajustar según tu setup

En `config/google_oauth.php`:

```php
// Para localhost/factapex/public/
define('GOOGLE_REDIRECT_URI', 'http://localhost/factapex/public/auth/google/callback');

// Para localhost directo (sin subcarpeta)
// define('GOOGLE_REDIRECT_URI', 'http://localhost/auth/google/callback');

// Para dominio real
// define('GOOGLE_REDIRECT_URI', 'https://factapex.com/auth/google/callback');
```

**IMPORTANTE**: La URI debe coincidir EXACTAMENTE con Google Cloud Console.

---

## 🛡️ Usar Middlewares

### En página de admin:
```php
<?php
require_once __DIR__ . '/../src/Middleware/protect_admin.php';
protectAdmin();

// Tu código admin aquí
?>
```

### En página de ejecutivo:
```php
<?php
require_once __DIR__ . '/../src/Middleware/protect_ejecutivo.php';
protectEjecutivo();

// Tu código ejecutivo aquí
?>
```

### En página de cliente:
```php
<?php
require_once __DIR__ . '/../src/Middleware/protect_cliente.php';
protectCliente();

// Tu código cliente aquí
?>
```

---

## 📊 Estructura de Sesión

Después de login exitoso:

```php
$_SESSION = [
    'user_id' => 1,
    'user_name' => 'Juan Pérez',
    'user_email' => 'juan@gmail.com',
    'user_role' => 'cliente',
    'user_provider' => 'google',
    'user_avatar' => 'https://lh3.googleusercontent.com/...',
    'last_activity' => 1700000000
];
```

---

## ⚡ Características Implementadas

✅ Google OAuth 2.0 con google/apiclient
✅ Solo clientes pueden usar OAuth
✅ Bloqueo para admin/ejecutivo
✅ Vinculación de cuentas locales
✅ Estados: activo, pendiente, bloqueado
✅ Middlewares de protección por rol
✅ Password hash con password_hash()
✅ Manejo de errores robusto
✅ Sesiones seguras con regenerate_id
✅ Compatible con localhost
✅ Seeding inicial de usuarios
✅ UI moderna con Tailwind CSS
✅ Avatar de Google
✅ Email verification flag

---

## 🐛 Troubleshooting

### Error: "redirect_uri_mismatch"
**Solución**: Verifica que la URI en `config/google_oauth.php` coincida EXACTAMENTE con Google Cloud Console.

### Error: "Client ID not configured"
**Solución**: Edita `config/google_oauth.php` y pega tus credenciales.

### Error: "Table users doesn't exist"
**Solución**: Ejecuta las migraciones SQL primero.

### Error: "Class GoogleClient not found"
**Solución**: Ejecuta `composer require google/apiclient:"^2.15"`

### Usuario admin no puede entrar
**Solución**: Admin debe usar login tradicional, NO Google OAuth.

### Redirección incorrecta después de login
**Solución**: Verifica las constantes en `config/app.php` y ajusta las rutas en `callback.php`.

---

## 📝 Credenciales de Prueba

```
┌─────────────────────────────────────────────┐
│  ADMIN                                      │
├─────────────────────────────────────────────┤
│  Email:    admin@factapex.com              │
│  Password: password                         │
│  Método:   Login tradicional SOLAMENTE     │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│  EJECUTIVO                                  │
├─────────────────────────────────────────────┤
│  Email:    ejecutivo@factapex.com          │
│  Password: password                         │
│  Método:   Login tradicional SOLAMENTE     │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│  CLIENTE (Local)                            │
├─────────────────────────────────────────────┤
│  Email:    cliente@factapex.com            │
│  Password: password                         │
│  Método:   Ambos (tradicional y Google)    │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│  CLIENTE (Nuevo con Google)                 │
├─────────────────────────────────────────────┤
│  Método:   Google OAuth únicamente         │
│  Se crea automáticamente al autenticar     │
│  Role:     cliente                          │
│  Estado:   pendiente                        │
└─────────────────────────────────────────────┘
```

---

## 🚀 Próximos Pasos

1. ✅ Ejecutar migraciones SQL
2. ✅ Instalar dependencias Composer
3. ✅ Configurar Google Cloud Console
4. ✅ Pegar credenciales en config
5. ✅ Ejecutar seeding de usuarios
6. ✅ Probar login tradicional
7. ✅ Probar login con Google
8. ✅ Verificar bloqueos

---

## 📚 Archivos Clave

| Archivo | Propósito |
|---------|-----------|
| `config/google_oauth.php` | Configuración OAuth |
| `src/Services/GoogleOAuthService.php` | Lógica de autenticación |
| `public/auth/google/login.php` | Iniciar flujo OAuth |
| `public/auth/google/callback.php` | Procesar respuesta |
| `src/Models/User.php` | Modelo con soporte OAuth |
| `database/migrations/update_users_oauth.sql` | Actualizar tabla |
| `database/seeds/seed_users.php` | Usuarios de prueba |

---

**🎉 ¡Todo listo! Ahora tienes Google OAuth funcionando perfectamente en localhost.**

Para cualquier duda, revisa los comentarios en cada archivo.
