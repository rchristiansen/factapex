/*
 Actualización de tabla users para Google OAuth
 Fecha: 23/11/2025
 Compatible con MySQL 8.0.44
 
 IMPORTANTE: Esta migración actualiza la tabla existente
*/

-- 1. Agregar columnas para OAuth si no existen
ALTER TABLE `users` 
ADD COLUMN IF NOT EXISTS `provider` ENUM('local', 'google') DEFAULT 'local' AFTER `role`,
ADD COLUMN IF NOT EXISTS `provider_id` VARCHAR(255) NULL AFTER `provider`,
ADD COLUMN IF NOT EXISTS `estado` ENUM('activo', 'pendiente', 'bloqueado') DEFAULT 'activo' AFTER `provider_id`,
ADD COLUMN IF NOT EXISTS `avatar` VARCHAR(500) NULL AFTER `estado`,
ADD COLUMN IF NOT EXISTS `email_verified` TINYINT(1) DEFAULT 0 AFTER `avatar`;

-- 2. Modificar password para que sea nullable (Google users no tienen password)
ALTER TABLE `users` 
MODIFY COLUMN `password` VARCHAR(255) NULL;

-- 3. Agregar índice único compuesto para provider + provider_id
ALTER TABLE `users`
ADD UNIQUE INDEX `idx_provider_id` (`provider`, `provider_id`);

-- 4. Agregar índice para búsquedas por estado
ALTER TABLE `users`
ADD INDEX `idx_estado` (`estado`);

-- 5. Actualizar usuarios existentes a provider 'local' si es NULL
UPDATE `users` 
SET `provider` = 'local' 
WHERE `provider` IS NULL;

-- 6. Actualizar usuarios existentes a estado 'activo' si es NULL
UPDATE `users` 
SET `estado` = 'activo' 
WHERE `estado` IS NULL;

-- 7. Verificar la estructura
SHOW COLUMNS FROM `users`;

-- 8. Ver usuarios actuales
SELECT id, name, email, role, provider, estado, created_at 
FROM users 
ORDER BY role, id;
