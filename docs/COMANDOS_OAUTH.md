# 🚀 Comandos Rápidos - Google OAuth (SIN COMPOSER)

## ⚠️ IMPORTANTE: NO usar Composer

Este proyecto **NO requiere Composer**. Todo funciona con **cURL nativo de PHP**.

---

## ✅ Verificar cURL

### Windows (XAMPP)
```bash
# Verificar si cURL está habilitado
php -m | findstr curl

# Ver versión de cURL
php -r "echo 'cURL: ' . curl_version()['version'] . PHP_EOL;"

# Test completo de cURL
php -r "
\$ch = curl_init('https://www.google.com');
curl_setopt(\$ch, CURLOPT_RETURNTRANSFER, true);
\$result = curl_exec(\$ch);
echo curl_error(\$ch) ? 'ERROR: ' . curl_error(\$ch) : '✅ cURL funciona correctamente';
curl_close(\$ch);
echo PHP_EOL;
"
```

### Habilitar cURL (si no está activo)
1. Edita: `c:\xampp\php\php.ini`
2. Busca: `;extension=curl`
3. Quita el `;` → `extension=curl`
4. Reinicia Apache

---

## 🗄️ Base de Datos

```bash
# Opción A: Actualizar tabla existente
mysql -u root -p factapex < database/migrations/update_users_oauth.sql

# Opción B: Crear tabla desde cero
mysql -u root -p factapex < database/migrations/create_users_full.sql
```

### Crear usuarios de prueba

```bash
php database/seeds/seed_users.php
```

### Verificar usuarios

```bash
mysql -u root -p factapex -e "SELECT id, name, email, role, provider, estado FROM users ORDER BY role;"
```

### Ver usuarios Google

```bash
mysql -u root -p factapex -e "SELECT id, name, email, provider, estado, created_at FROM users WHERE provider='google';"
```

### Ver usuarios pendientes

```bash
mysql -u root -p factapex -e "SELECT id, name, email, role, estado FROM users WHERE estado='pendiente';"
```

### Activar usuario pendiente

```bash
mysql -u root -p factapex -e "UPDATE users SET estado='activo' WHERE id=5;"
```

### Bloquear usuario

```bash
mysql -u root -p factapex -e "UPDATE users SET estado='bloqueado' WHERE email='usuario@example.com';"
```

### Eliminar usuario de prueba

```bash
mysql -u root -p factapex -e "DELETE FROM users WHERE email='test@gmail.com';"
```

---

## 🔧 Configuración

### Ver configuración de Google

```bash
php -r "require 'config/google_oauth.php'; echo 'Client ID: ' . GOOGLE_CLIENT_ID . PHP_EOL;"
```

### Verificar redirect URI

```bash
php -r "require 'config/google_oauth.php'; echo 'Redirect URI: ' . GOOGLE_REDIRECT_URI . PHP_EOL;"
```

### Probar conexión con Google (requiere credenciales válidas)

```bash
php -r "
require 'vendor/autoload.php';
require 'config/google_oauth.php';
\$client = new Google\Client();
\$client->setClientId(GOOGLE_CLIENT_ID);
\$client->setClientSecret(GOOGLE_CLIENT_SECRET);
echo 'Google Client configurado correctamente' . PHP_EOL;
"
```

---

## 🧪 Testing

### Probar seeding nuevamente

```bash
php database/seeds/seed_users.php
```

### Ver logs de Apache

```bash
# Windows (XAMPP)
type c:\xampp\apache\logs\error.log

# O en tiempo real
powershell Get-Content c:\xampp\apache\logs\error.log -Wait -Tail 20
```

### Ver últimos errores PHP

```bash
php -r "error_log('Test log from CLI');"
```

---

## 🔐 Seguridad

### Generar password hash

```bash
php -r "echo password_hash('mi_password', PASSWORD_DEFAULT) . PHP_EOL;"
```

### Verificar password

```bash
php -r "
\$hash = '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
\$password = 'password';
echo password_verify(\$password, \$hash) ? 'VÁLIDO' : 'INVÁLIDO';
echo PHP_EOL;
"
```

### Crear nuevo admin

```bash
php -r "
\$password = password_hash('nueva_password', PASSWORD_DEFAULT);
echo \"INSERT INTO users (name, email, password, role, provider, estado) VALUES ('Nuevo Admin', 'nuevo@factapex.com', '\$password', 'admin', 'local', 'activo');\" . PHP_EOL;
"
```

---

## 📊 Consultas Útiles

### Contar usuarios por rol

```bash
mysql -u root -p factapex -e "SELECT role, COUNT(*) as total FROM users GROUP BY role;"
```

### Contar usuarios por provider

```bash
mysql -u root -p factapex -e "SELECT provider, COUNT(*) as total FROM users GROUP BY provider;"
```

### Contar usuarios por estado

```bash
mysql -u root -p factapex -e "SELECT estado, COUNT(*) as total FROM users GROUP BY estado;"
```

### Ver últimos logins

```bash
mysql -u root -p factapex -e "SELECT name, email, role, last_login FROM users WHERE last_login IS NOT NULL ORDER BY last_login DESC LIMIT 10;"
```

### Usuarios sin login nunca

```bash
mysql -u root -p factapex -e "SELECT name, email, role, created_at FROM users WHERE last_login IS NULL;"
```

---

## 🧹 Limpieza

### Eliminar usuarios de prueba Google

```bash
mysql -u root -p factapex -e "DELETE FROM users WHERE provider='google' AND email LIKE '%@gmail.com';"
```

### Reset tabla users (¡CUIDADO!)

```bash
mysql -u root -p factapex -e "TRUNCATE TABLE users;"
php database/seeds/seed_users.php
```

### Limpiar logs de Apache

```bash
# Windows
del c:\xampp\apache\logs\error.log
```

---

## 🚀 Desarrollo

### Iniciar servidor PHP built-in (alternativo a XAMPP)

```bash
cd c:\xampp\htdocs\factapex\public
php -S localhost:8000
```

### Ver rutas disponibles

```bash
php -r "
require 'vendor/autoload.php';
require 'config/routes.php';
echo 'Rutas OAuth:' . PHP_EOL;
echo '  GET  /auth/google/login' . PHP_EOL;
echo '  GET  /auth/google/callback' . PHP_EOL;
"
```

### Comprobar versión de PHP

```bash
php -v
```

### Comprobar extensiones necesarias

```bash
php -m | findstr -i "curl openssl json"
```

---

## 📦 Composer

### Actualizar dependencias

```bash
composer update
```

### Ver dependencias instaladas

```bash
composer show
```

### Reinstalar composer desde cero

```bash
rmdir /s /q vendor
del composer.lock
composer install
```

---

## 🔄 Git (opcional)

### Ignorar credenciales

```bash
# .gitignore
echo config/google_oauth.php >> .gitignore
echo .env >> .gitignore
```

### Commit de implementación OAuth

```bash
git add .
git commit -m "✨ Implementar Google OAuth para clientes"
git push
```

---

## 🎯 Atajos Útiles

### Verificación completa del sistema

```bash
# Script todo en uno
php -r "
echo '🔍 Verificando sistema...' . PHP_EOL . PHP_EOL;

// Composer
echo '📦 Composer: ';
echo file_exists('vendor/autoload.php') ? '✅' : '❌';
echo PHP_EOL;

// Google Client
echo '🔐 Google Client: ';
echo class_exists('Google\Client', true) ? '✅' : '❌';
echo PHP_EOL;

// Config
echo '⚙️  Config OAuth: ';
require 'config/google_oauth.php';
echo isGoogleOAuthConfigured() ? '✅' : '❌';
echo PHP_EOL;

echo PHP_EOL . '✨ Verificación completa' . PHP_EOL;
"
```

### Crear usuario rápido

```bash
# Admin
mysql -u root -p factapex -e "INSERT INTO users (name, email, password, role, provider, estado) VALUES ('Admin', 'admin@test.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'local', 'activo');"

# Cliente
mysql -u root -p factapex -e "INSERT INTO users (name, email, password, role, provider, estado) VALUES ('Cliente', 'cliente@test.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', 'local', 'activo');"
```

---

## 📝 Notas

- Todos los comandos SQL asumen:
  - Usuario: `root`
  - Base de datos: `factapex`
  - Password: Se solicita interactivamente

- Los hash de ejemplo corresponden a password: `password`

- Para Windows PowerShell, algunos comandos pueden necesitar ajustes

---

## 🎉 Quick Test Completo

```bash
# 1. Instalar
composer install

# 2. Migrar
mysql -u root -p factapex < database/migrations/update_users_oauth.sql

# 3. Seed
php database/seeds/seed_users.php

# 4. Verificar
mysql -u root -p factapex -e "SELECT id, name, email, role, provider FROM users;"

# 5. Abrir navegador
start http://localhost/factapex/public/login

# ✅ ¡Listo!
```

---

**💡 Tip**: Guarda este archivo como referencia rápida para comandos comunes.
