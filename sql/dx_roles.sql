CREATE TABLE dx_ROLES (
    ID            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    NAME          VARCHAR(50) NOT NULL UNIQUE,  -- Nom du rôle, par exemple "admin", "client", etc.
    DESCRIPTION   TEXT NULL,  -- Description optionnelle du rôle
    CREATED_AT    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    DELETED_AT    DATETIME NULL,  -- Ajout de la colonne pour gestion de suppression logique
    CONSTRAINT CHK_NAME_LENGTH CHECK (CHAR_LENGTH(NAME) > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO dx_ROLES (NAME, DESCRIPTION) 
VALUES 
('SuperAdmin', 'Rôle avec des privilèges complets pour administrer tous les aspects du site, y compris la gestion des rôles et des utilisateurs.'),
('Administrateur', 'Rôle ayant des privilèges élevés pour gérer les utilisateurs et le contenu du site, sans gestion des rôles.');
