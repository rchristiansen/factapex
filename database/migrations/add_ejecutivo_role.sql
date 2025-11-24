/*
 Script de migración para agregar el rol "ejecutivo"
 Fecha: 23/11/2025
 Descripción: Actualiza la tabla users para incluir el rol ejecutivo
*/

-- 1. Modificar el ENUM de la columna role para incluir 'ejecutivo'
ALTER TABLE `users` 
MODIFY COLUMN `role` ENUM('admin', 'ejecutivo', 'cliente') 
CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci 
NULL DEFAULT 'cliente';

-- 2. Crear un usuario ejecutivo de ejemplo (password: password)
-- La contraseña hasheada es: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`, `updated_at`) 
VALUES 
('Ejecutivo Demo', 'ejecutivo@factapex.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ejecutivo', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    `role` = 'ejecutivo',
    `updated_at` = NOW();

-- 3. Verificar la estructura actualizada
SHOW COLUMNS FROM `users` WHERE Field = 'role';

-- 4. Verificar los usuarios existentes
SELECT id, name, email, role, created_at FROM users ORDER BY role, id;
