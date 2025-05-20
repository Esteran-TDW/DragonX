<?php
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}
class Roles
{
    private $db;  // Instance de la base de données
    private $table = 'dx_ROLES';  // Table des rôles

    public function __construct()
    {
        global $Database;
        
        // Récupération de l'objet Database
        if (!isset($Database)) {
            throw new Exception('Database object not found.');
        } else {
            $this->db = $Database;
        }

    }

    // Créer un nouveau rôle
    public function createRole($name, $description)
    {
        $query = "INSERT INTO {$this->table} (NAME, DESCRIPTION) VALUES (:name, :description)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    // Mettre à jour un rôle
    public function updateRole($id, $name, $description)
    {
        $query = "UPDATE {$this->table} SET NAME = :name, DESCRIPTION = :description WHERE ID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Supprimer un rôle
    public function deleteRole($id)
    {
        $query = "UPDATE {$this->table} SET DELETED_AT = NOW() WHERE ID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Récupérer un rôle par son nom
    public function getRoleByName($name)
    {
        $query = "SELECT * FROM {$this->table} WHERE NAME = :name AND DELETED_AT IS NULL";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer un rôle par son identifiant
    public function getRoleById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE ID = :id AND DELETED_AT IS NULL";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
