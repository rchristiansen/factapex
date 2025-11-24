/*
 Tabla COMPLETA users para producción
 Compatible con MySQL 8.0.44
 Incluye soporte para Google OAuth
*/

-- Eliminar tabla si existe (solo para testing, comentar en producción)
-- DROP TABLE IF EXISTS `users`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NULL COMMENT 'NULL para usuarios OAuth',
  `role` ENUM('admin', 'ejecutivo', 'cliente') NOT NULL DEFAULT 'cliente',
  `provider` ENUM('local', 'google') NOT NULL DEFAULT 'local',
  `provider_id` VARCHAR(255) NULL COMMENT 'ID del proveedor OAuth',
  `estado` ENUM('activo', 'pendiente', 'bloqueado') NOT NULL DEFAULT 'activo',
  `avatar` VARCHAR(500) NULL COMMENT 'URL de imagen de perfil',
  `email_verified` TINYINT(1) NOT NULL DEFAULT 0,
  `company_id` INT NULL,
  `last_login` TIMESTAMP NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email` (`email`),
  UNIQUE INDEX `idx_provider_id` (`provider`, `provider_id`),
  INDEX `idx_role` (`role`),
  INDEX `idx_estado` (`estado`),
  INDEX `idx_company` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario ADMIN inicial
INSERT INTO `users` 
(`name`, `email`, `password`, `role`, `provider`, `estado`, `email_verified`, `created_at`) 
VALUES 
(
  'Administrador', 
  'admin@factapex.com', 
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
  'admin', 
  'local', 
  'activo',
  1,
  NOW()
),
(
  'Ejecutivo Demo', 
  'ejecutivo@factapex.com', 
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
  'ejecutivo', 
  'local', 
  'activo',
  1,
  NOW()
),
(
  'Cliente Demo', 
  'cliente@factapex.com', 
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
  'cliente', 
  'local', 
  'activo',
  1,
  NOW()
)
ON DUPLICATE KEY UPDATE 
  `updated_at` = NOW();

-- Verificar usuarios creados
SELECT id, name, email, role, provider, estado, created_at 
FROM users 
ORDER BY role, id;
