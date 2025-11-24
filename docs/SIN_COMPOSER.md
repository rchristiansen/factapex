# ⚠️ IMPORTANTE: Este proyecto NO usa Composer

## 🚫 Sin Dependencias Externas

Esta implementación de Google OAuth **NO requiere Composer** ni la biblioteca `google/apiclient`.

### ✅ Ventajas

- ✅ **Cero dependencias**: Solo PHP nativo + cURL
- ✅ **Más ligero**: Sin carpeta `vendor/` pesada
- ✅ **Más rápido**: Sin autoloader de Composer
- ✅ **Más simple**: Menos archivos, más control
- ✅ **Portátil**: Funciona en cualquier servidor con PHP + cURL

---

## 🔧 Requisitos

### PHP 8.0+
```bash
php -v
```

### Extensión cURL habilitada
```bash
php -m | findstr curl
```

Si no aparece `curl`:
1. Edita `c:\xampp\php\php.ini`
2. Busca `;extension=curl`
3. Quita el `;` → `extension=curl`
4. Reinicia Apache

### Extensiones adicionales (ya vienen con PHP):
- `openssl` (para HTTPS)
- `json` (para parsear respuestas)

---

## 📁 Archivos NO Necesarios

**NO necesitas estos archivos:**
- ❌ `composer.json`
- ❌ `composer.lock`
- ❌ `vendor/` (carpeta completa)
- ❌ `vendor/autoload.php`

---

## 🔄 Cómo Funciona

### En lugar de usar `google/apiclient`:

**❌ Antes (con Composer):**
```php
require 'vendor/autoload.php';
use Google\Client;

$client = new Client();
$client->setClientId('...');
// etc.
```

**✅ Ahora (sin Composer):**
```php
// Usar cURL directo para comunicarse con Google
$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
// etc.
```

---

## 🚀 Setup Simplificado

```bash
# 1. Habilitar cURL (si no está activo)
# Edita php.ini → extension=curl

# 2. Migrar base de datos
mysql -u root -p factapex < database/migrations/update_users_oauth.sql

# 3. Seed usuarios
php database/seeds/seed_users.php

# 4. Configurar Google OAuth
# Edita config/google_oauth.php

# 5. ¡Listo!
```

**NO ejecutar:**
```bash
composer install  # ❌ NO NECESARIO
```

---

## 📚 Documentación

Lee estos archivos (ignora referencias a Composer):
- `docs/QUICK_START_OAUTH.md` - Setup rápido
- `docs/COMANDOS_OAUTH.md` - Comandos útiles
- `docs/GOOGLE_OAUTH_GUIDE.md` - Guía completa

---

## 🐛 Troubleshooting

### ❌ "Call to undefined function curl_init()"
**Causa:** cURL no está habilitado  
**Solución:** Edita `php.ini` y descomenta `extension=curl`

### ❌ "Class GoogleClient not found"
**Causa:** Ninguna, no usamos esa clase  
**Solución:** Ignorar. Este error NO debería aparecer

### ✅ Todo funciona con cURL nativo
```bash
# Test rápido
php -r "
\$ch = curl_init('https://www.google.com');
curl_setopt(\$ch, CURLOPT_RETURNTRANSFER, true);
\$result = curl_exec(\$ch);
echo curl_error(\$ch) ? 'ERROR' : 'cURL OK';
curl_close(\$ch);
"
```

---

**💡 Esta implementación es más simple, rápida y portable que usar Composer.**
