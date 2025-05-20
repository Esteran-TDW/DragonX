<?php
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}

class Users
{
    private $db;  // Instance de la base de données
    private $table = 'dx_USERS';  // Table des utilisateurs

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

    // Créer un nouvel utilisateur
    public function createUser($username, $email, $password, $roleId)
    {
        // Hachage du mot de passe
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Requête d'insertion
        $query = "INSERT INTO {$this->table} (USERNAME, EMAIL, PASSWORD, ROLE_ID) 
                  VALUES (:username, :email, :password, :role_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':role_id', $roleId);

        return $stmt->execute();
    }

    // Mettre à jour un utilisateur
    public function updateUser($id, $username, $email, $password = null, $roleId)
    {
        $query = "UPDATE {$this->table} SET USERNAME = :username, EMAIL = :email, ROLE_ID = :role_id";

        // Si un mot de passe est fourni, on le hache et on l'ajoute à la requête
        if ($password) {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $query .= ", PASSWORD = :password";
        }

        $query .= " WHERE ID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role_id', $roleId);
        $stmt->bindParam(':id', $id);

        if ($password) {
            $stmt->bindParam(':password', $passwordHash);
        }

        return $stmt->execute();
    }

    // Supprimer un utilisateur
    public function deleteUser($id)
    {
        $query = "UPDATE {$this->table} SET DELETED_AT = NOW() WHERE ID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Récupérer un utilisateur par son identifiant
    public function getUserById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE ID = :id AND DELETED_AT IS NULL";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Vérifier les identifiants de l'utilisateur (login)
    public function authenticate($username, $password)
    {
        $query = "SELECT * FROM {$this->table} WHERE USERNAME = :username AND DELETED_AT IS NULL";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['PASSWORD'])) {
            return $user;  // Connexion réussie, retourner l'utilisateur
        }

        return null;  // Connexion échouée
    }
}