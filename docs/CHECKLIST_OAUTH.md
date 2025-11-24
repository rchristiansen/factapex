# 🎯 Checklist de Implementación - Google OAuth

## ✅ Pre-requisitos

- [ ] PHP 8.2.12 instalado
- [ ] MySQL 8.0.44 corriendo
- [ ] XAMPP iniciado (Apache + MySQL)
- [ ] Composer instalado
- [ ] Cuenta de Google activa
- [ ] Acceso a Google Cloud Console

---

## 📦 Instalación de Dependencias

- [ ] Ejecutar: `composer install`
- [ ] Verificar: `vendor/google/apiclient` existe
- [ ] Sin errores de instalación

---

## 🗄️ Base de Datos

### Migraciones
- [ ] Ejecutar: `update_users_oauth.sql` o `create_users_full.sql`
- [ ] Verificar columnas nuevas:
  - [ ] `provider` ENUM('local', 'google')
  - [ ] `provider_id` VARCHAR(255)
  - [ ] `estado` ENUM('activo', 'pendiente', 'bloqueado')
  - [ ] `avatar` VARCHAR(500)
  - [ ] `email_verified` TINYINT(1)
  - [ ] `last_login` TIMESTAMP

### Seeding
- [ ] Ejecutar: `php database/seeds/seed_users.php`
- [ ] Verificar usuarios creados:
  - [ ] admin@factapex.com (local, activo)
  - [ ] ejecutivo@factapex.com (local, activo)
  - [ ] cliente@factapex.com (local, activo)
  - [ ] cliente.google@factapex.com (google, pendiente)

### Verificación
```sql
SELECT id, name, email, role, provider, estado FROM users;
```
- [ ] Al menos 4 usuarios visibles
- [ ] Sin errores en consulta

---

## ☁️ Google Cloud Console

### Proyecto
- [ ] Proyecto creado (ej: "Factapex OAuth")
- [ ] Proyecto seleccionado actualmente

### APIs
- [ ] "People API" habilitada
- [ ] O "Google+ API" habilitada (legacy)

### Credenciales OAuth 2.0
- [ ] ID de cliente creado
- [ ] Tipo: Aplicación web
- [ ] **Orígenes autorizados JavaScript:**
  - [ ] `http://localhost`
  - [ ] `http://localhost:80`
  
- [ ] **URIs de redirección autorizados:**
  - [ ] `http://localhost/factapex/public/auth/google/callback`
  - [ ] (Ajustar según tu ruta)

### Pantalla de consentimiento
- [ ] Configurada (Externo)
- [ ] Nombre de app: "Factapex"
- [ ] Email de soporte configurado
- [ ] Logo (opcional)
- [ ] Ámbitos: email, profile

### Credenciales copiadas
- [ ] CLIENT_ID copiado
- [ ] CLIENT_SECRET copiado
- [ ] Formato correcto (.apps.googleusercontent.com)

---

## ⚙️ Configuración del Proyecto

### config/google_oauth.php
- [ ] Archivo existe
- [ ] `GOOGLE_CLIENT_ID` pegado (sin "TU_GOOGLE...")
- [ ] `GOOGLE_CLIENT_SECRET` pegado (sin "TU_GOOGLE...")
- [ ] `GOOGLE_REDIRECT_URI` coincide con Google Console
- [ ] URI NO tiene espacios ni typos

### config/app.php
- [ ] `PUBLIC_PATH` definido correctamente
- [ ] Base URL del proyecto configurada

### Permisos de archivos
- [ ] Carpeta `storage/` con permisos de escritura
- [ ] Carpeta `storage/logs/` con permisos de escritura

---

## 🔧 Archivos Generados

### Backend
- [ ] `src/Services/GoogleOAuthService.php` existe
- [ ] `src/Models/User.php` actualizado con `create()` y `update()`
- [ ] `src/Middleware/protect_admin.php` existe
- [ ] `src/Middleware/protect_ejecutivo.php` existe
- [ ] `src/Middleware/protect_cliente.php` existe
- [ ] `src/Controllers/AuthController.php` actualizado

### Rutas OAuth
- [ ] `public/auth/google/login.php` existe
- [ ] `public/auth/google/callback.php` existe

### Vistas
- [ ] `views/auth/login_with_google.php` existe
- [ ] O botón de Google agregado a login existente

---

## 🧪 Testing

### Test 1: Login Admin (Tradicional)
- [ ] Ir a: `http://localhost/factapex/public/login`
- [ ] Email: `admin@factapex.com`
- [ ] Password: `password`
- [ ] ✅ Login exitoso
- [ ] ✅ Redirige a dashboard admin
- [ ] ✅ Sesión creada con `user_role = 'admin'`

### Test 2: Login Ejecutivo (Tradicional)
- [ ] Email: `ejecutivo@factapex.com`
- [ ] Password: `password`
- [ ] ✅ Login exitoso
- [ ] ✅ Redirige a dashboard ejecutivo
- [ ] ✅ Sesión con `user_role = 'ejecutivo'`

### Test 3: Login Cliente (Tradicional)
- [ ] Email: `cliente@factapex.com`
- [ ] Password: `password`
- [ ] ✅ Login exitoso
- [ ] ✅ Redirige a dashboard cliente

### Test 4: Login con Google (Cliente Nuevo)
- [ ] Clic en "Continuar con Google"
- [ ] Seleccionar cuenta Google
- [ ] ✅ Autorización solicitada
- [ ] ✅ Redirige a callback
- [ ] ✅ Usuario creado en DB:
  ```sql
  SELECT * FROM users WHERE email = 'TU_EMAIL_GOOGLE@gmail.com';
  ```
  - [ ] `role = 'cliente'`
  - [ ] `provider = 'google'`
  - [ ] `estado = 'pendiente'`
  - [ ] `provider_id` tiene valor
  - [ ] `avatar` tiene URL
- [ ] ✅ Sesión creada
- [ ] ✅ Redirige a dashboard cliente

### Test 5: Login con Google (Cliente Existente)
- [ ] Crear usuario local primero:
  ```sql
  INSERT INTO users (name, email, password, role, provider) 
  VALUES ('Test User', 'test@gmail.com', 'xxx', 'cliente', 'local');
  ```
- [ ] Login con Google usando `test@gmail.com`
- [ ] ✅ Usuario actualizado:
  - [ ] `provider = 'google'`
  - [ ] `provider_id` agregado
  - [ ] Sin duplicados
- [ ] ✅ Login exitoso

### Test 6: Bloqueo Admin por Google ❌
- [ ] Intentar login Google con `admin@factapex.com`
- [ ] ✅ Debe mostrar error: "Cuenta interna, use login tradicional"
- [ ] ✅ NO debe permitir acceso
- [ ] ✅ Redirige a login

### Test 7: Bloqueo Ejecutivo por Google ❌
- [ ] Intentar login Google con `ejecutivo@factapex.com`
- [ ] ✅ Debe mostrar error similar
- [ ] ✅ NO debe permitir acceso

### Test 8: Usuario Bloqueado
- [ ] Bloquear usuario:
  ```sql
  UPDATE users SET estado = 'bloqueado' WHERE email = 'cliente@factapex.com';
  ```
- [ ] Intentar login (tradicional o Google)
- [ ] ✅ Debe mostrar: "Cuenta bloqueada"
- [ ] ✅ NO debe permitir acceso

### Test 9: Sesión y Avatar
- [ ] Login con Google exitoso
- [ ] Verificar sesión:
  ```php
  var_dump($_SESSION);
  ```
  - [ ] `user_avatar` tiene URL de Google
  - [ ] `user_provider = 'google'`
  - [ ] `email_verified = 1`

### Test 10: Logout
- [ ] Click en logout
- [ ] ✅ Sesión destruida
- [ ] ✅ Redirige a login
- [ ] ✅ No puede acceder a dashboard sin login

---

## 🛡️ Seguridad

### Validaciones
- [ ] Admin no puede usar Google OAuth ✅
- [ ] Ejecutivo no puede usar Google OAuth ✅
- [ ] Solo clientes pueden usar Google OAuth ✅
- [ ] Usuarios bloqueados no pueden entrar ✅
- [ ] Password es NULL para usuarios Google ✅
- [ ] `session_regenerate_id()` al crear sesión ✅

### Tokens
- [ ] CSRF token implementado (si aplica)
- [ ] Token de sesión único generado
- [ ] Timeout de sesión funciona (2 horas)

---

## 📊 Monitoreo

### Logs
- [ ] Verificar logs de Apache: `c:\xampp\apache\logs\error.log`
- [ ] Sin errores PHP
- [ ] Sin warnings de Google Client

### Base de Datos
- [ ] Ver usuarios activos:
  ```sql
  SELECT COUNT(*) FROM users WHERE estado = 'activo';
  ```
- [ ] Ver usuarios Google:
  ```sql
  SELECT COUNT(*) FROM users WHERE provider = 'google';
  ```
- [ ] Ver últimos logins:
  ```sql
  SELECT name, email, last_login FROM users ORDER BY last_login DESC LIMIT 10;
  ```

---

## 🚀 Deployment (Futuro)

### Para producción HTTPS:
- [ ] Cambiar `GOOGLE_REDIRECT_URI` a HTTPS
- [ ] Agregar URI HTTPS en Google Console
- [ ] Certificado SSL instalado
- [ ] Forzar HTTPS en Apache/Nginx

### Variables de entorno:
- [ ] `GOOGLE_CLIENT_ID` en .env
- [ ] `GOOGLE_CLIENT_SECRET` en .env
- [ ] No commitear credenciales a Git

---

## 📚 Documentación

- [ ] `docs/GOOGLE_OAUTH_GUIDE.md` leído
- [ ] `docs/QUICK_START_OAUTH.md` seguido
- [ ] `docs/EJEMPLOS_OAUTH.php` revisado
- [ ] Equipo capacitado en flujo OAuth

---

## ✨ Post-Implementación

- [ ] Notificar a usuarios de nueva opción de login
- [ ] Documentar credenciales de prueba
- [ ] Crear proceso de aprobación para usuarios pendientes
- [ ] Configurar emails de bienvenida (futuro)
- [ ] Configurar notificaciones a admin (nuevos registros)

---

## 🎉 Checklist Final

- [ ] ✅ Todos los tests pasaron
- [ ] ✅ Sin errores en consola de navegador
- [ ] ✅ Sin errores en logs de PHP
- [ ] ✅ Admin puede entrar (tradicional)
- [ ] ✅ Ejecutivo puede entrar (tradicional)
- [ ] ✅ Cliente puede entrar (ambos métodos)
- [ ] ✅ Google OAuth funciona 100%
- [ ] ✅ Bloqueos funcionan correctamente
- [ ] ✅ Documentación completa
- [ ] ✅ Equipo capacitado

---

**🚀 ¡Implementación Completada!**

Si todos los checkboxes están marcados, tu sistema de Google OAuth está funcionando perfectamente.
