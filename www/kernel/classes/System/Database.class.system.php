<?php

if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}

class Database extends System
{
    private $pdo;
    private $stmt;
    private $error;
    public $where = [];    // Pour stocker la condition WHERE
    public $bind = [];     // Pour stocker les valeurs à lier

    //   private $config;

    // Construction de la connexion
    public function __construct()
    {
        // Chargement de la configuration de la base de données à partir de config.php
     parent::__construct(); // Important pour appeler le constructeur de System


         // Vérifier que $this->config est bien défini
        if (empty($this->config)) {
            throw new Exception("Configuration non chargée");
        }
     
        global $Security;
        // Récupération de l'objet Security
        if (!isset($Security)) {
            throw new Exception('Security object not found.');
        }
        
        // Vérification de l'objet $security
        if (!$Security || !($Security instanceof Security)) {
            throw new Exception("L'objet Security est requis pour la classe Database");
        }

        // Récupération de la configuration selon l'environnement
        $db_config = $this->config['db'];

        $db_password = $Security->decrypt($db_config['password']);

        try {
            $dsn = 'mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['name'] . '';
            $this->pdo = new PDO($dsn, $db_config['user'], $db_password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Connection Database failed: " . $this->error);
        } 
    }

    // Préparer et exécuter les requêtes avec la gestion des binds
    private function prepareAndExecute($query, $params)
    {
        try {
            $this->stmt = $this->pdo->prepare($query);
            foreach ($params as $key => $value) {
                $this->stmt->bindValue($key + 1, $value);
            }
            $this->stmt->execute();
            return $this;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Query failed: " . $this->error);
        }
    }

    // Fonction SELECT avec options dynamiques (WHERE, LIMIT, ORDER)
    public function select($columns, $table, $params = [])
    {
        // S'assurer que $this->bind contient bien des valeurs si la clause WHERE est utilisée
        if (!empty($this->bind)) {
            $whereClause = $this->buildWhereClause();
        } else {
            $whereClause = ''; // Pas de condition WHERE
        }

        // Limite et ordre si définis
        $limit = isset($params['limit']) ? 'LIMIT ' . (int) $params['limit'] : '';

        // Traitement d'ORDER BY pour plusieurs colonnes
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                $orderBy = 'ORDER BY ' . implode(', ', $params['order_by']);
            } else {
                $orderBy = 'ORDER BY ' . $params['order_by'];
            }
        } else {
            $orderBy = '';
        }

        // Construction de la requête complète
        $query = "SELECT $columns FROM $table $whereClause $orderBy $limit";

        // Exécution de la requête et récupération des résultats
        try {
            // Si $this->bind contient des paramètres, on les passe à prepareAndExecute
            $result = $this->prepareAndExecute($query, $this->bind);

            // Récupérer les résultats
            $results = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Query failed: " . $this->error);
        }

        // Renvoyer les résultats ou un tableau vide si rien trouvé
        $this->close();  // Fermer la connexion après avoir récupéré les données
        return $results ? $results : [];
    }

    // Fonction INSERT avec des données à lier
    public function insert($table, $columns, $values)
    {
        $columnsStr = implode(',', $columns);
        $placeholders = implode(',', array_fill(0, count($values), '?'));

        $query = "INSERT INTO $table ($columnsStr) VALUES ($placeholders)";

        // Exécution de la requête et renvoi du dernier ID inséré
        $this->prepareAndExecute($query, $values);
        $this->close();
        return $this->pdo->lastInsertId();
    }

    // Fonction UPDATE avec options dynamiques (WHERE, SET, and Bind values)
    public function update($table, $columns, $values)
    {
        // Créer la partie SET de la requête avec les colonnes et placeholders
        $setPart = '';
        foreach ($columns as $column) {
            $setPart .= "$column = ?, ";
        }
        $setPart = rtrim($setPart, ', ');  // Suppression de la dernière virgule

        $whereClause = $this->buildWhereClause();

        // Créer la requête UPDATE
        $query = "UPDATE $table SET $setPart $whereClause";

        // Lier les valeurs dynamiquement
        $allValues = array_merge($values, $this->bind);
        $this->prepareAndExecute($query, $allValues);
        $this->close();

        return $this->stmt->rowCount();
    }

    // Fonction DELETE avec gestion du WHERE
    public function delete($table)
    {
        $whereClause = $this->buildWhereClause();
        $query = "DELETE FROM $table $whereClause";

        // Exécution de la requête et renvoi du nombre de lignes affectées
        $this->prepareAndExecute($query, $this->bind);
        $this->close();
        return $this->stmt->rowCount();
    }

    // Construction dynamique de la clause WHERE
    private function buildWhereClause()
    {
        if (!empty($this->where)) {
            return 'WHERE ' . implode(' AND ', $this->where);
        }
        return '';
    }

    // Méthode pour effacer les paramètres WHERE et bind après exécution
    public function reset()
    {
        $this->where = [];
        $this->bind = [];
    }

    // Méthode pour fermer la connexion à la base de données
    private function close()
    {
        $this->reset();
        $this->pdo = null;
        $this->stmt = null;
    }
}

?>