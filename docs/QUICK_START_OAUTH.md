# 🚀 Quick Start - Google OAuth (SIN COMPOSER)

## ⚠️ IMPORTANTE: Este proyecto NO usa Composer

La implementación usa **cURL nativo de PHP** para comunicarse con Google OAuth.

---

## 1️⃣ Verificar que cURL esté habilitado (30 segundos)

Reinicia Apache en XAMPP.

### Verificar en terminal:
```bash
php -m | findstr curl
```

Debe mostrar: `curl`

---

## 2️⃣ Actualizar Base de Datos (1 minuto)

```bash
mysql -u root -p factapex < database/migrations/update_users_oauth.sql
php database/seeds/seed_users.php
```

---

## 3️⃣ Configurar Google Console (2 minutos)

### A. Crear Proyecto
- https://console.cloud.google.com/
- Nuevo Proyecto → "Factapex OAuth"

### B. Habilitar API
- APIs y servicios → Biblioteca
- Buscar "People API" → Habilitar

### C. Crear Credenciales
1. Credenciales → Crear → OAuth 2.0
2. Orígenes: `http://localhost`
3. Redirección: `http://localhost/factapex/public/auth/google/callback`
4. **Copiar CLIENT_ID y CLIENT_SECRET**

## 4️⃣ Pegar Credenciales (30 segundos)

Edita `config/google_oauth.php`:
```php
define('GOOGLE_CLIENT_ID', 'PEGA_AQUI.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'PEGA_AQUI');
```

## 5️⃣ Probar (1 minuto)

```bash
# Iniciar XAMPP
# MySQL + Apache

# Abrir navegador:
http://localhost/factapex/public/login
```

### Login Admin:
- Email: `admin@factapex.com`
- Password: `password`

### Login Google (Cliente):
- Clic en "Continuar con Google"
- Selecciona tu cuenta
- ✅ Debe crear usuario y entrar

---

## 📋 Checklist Rápido

- [ ] cURL habilitado en PHP
- [ ] SQL migrado
- [ ] Usuarios seeded
- [ ] Google project creado
- [ ] API habilitada
- [ ] Credenciales copiadas en config
- [ ] Probado login admin (tradicional)
- [ ] Probado login Google (cliente)

---

## ⚡ Sin Composer - Diferencias

### ❌ NO ejecutar:
```bash
composer install  # NO NECESARIO
```

### ✅ En su lugar:
- Todo usa **cURL nativo de PHP**
- Sin dependencias externas
- Sin carpeta `vendor/`
- Sin `autoload.php` de Composer

---

## 🔧 Verificación

```bash
# Ver que cURL funciona
php -r "echo curl_version()['version'];"
```

---

## 🐛 Problemas Comunes

### ❌ Call to undefined function curl_init()
→ cURL no está habilitado. Edita `php.ini` y descomenta `extension=curl`

### ❌ redirect_uri_mismatch
→ URI en Google Console debe ser EXACTA: `http://localhost/factapex/public/auth/google/callback`

### ❌ Class GoogleClient not found
→ ✅ **IGNORAR**: No usamos google/apiclient, usamos cURL directo

---

## 📚 Documentación Completa

Ver `docs/GOOGLE_OAUTH_GUIDE.md` para detalles completos.

**Nota**: Ignora las referencias a Composer en otros docs.

---

**🎉 ¡Listo en 5 minutos SIN Composer!**
