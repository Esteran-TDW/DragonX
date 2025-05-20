<?php
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}

class MenuBuilder
{
    private $db;
    private $menuTable = 'menu';
    public $menu;

    public function __construct()
    {

        global $Database;
        
        // Récupération de l'objet Database
        if (!isset($Database)) {
            throw new Exception('Database object not found.');
        } else {
            $this->db = $Database;
        }

        $this->menu = $this->getMenu();

    }

    public function getMenu()
    {
        $columns = '*';
        $params = [
            'order_by' => [
                'ID',          // Tri par `ID`
                'POSITION ASC' // Tri par `POSITION` en ordre croissant
            ]
        ];

        // Récupération des menus depuis la base de données
        $menu = $this->db->select($columns, $this->menuTable, $params);

        // Étape 1 : Indexation des éléments par ID
        $menuIndexed = [];
        foreach ($menu as $item) {
            $item['SUBMENU'] = []; // Toujours initialiser un tableau vide pour éviter les erreurs
            $menuIndexed[$item['ID']] = $item;
        }

        // Étape 2 : Construction de la hiérarchie parent-enfant
        $menuTree = [];
        foreach ($menuIndexed as $id => &$item) {
            if (!empty($item['PARENT_ID']) && isset($menuIndexed[$item['PARENT_ID']])) {
                // Ajouter l'élément comme enfant de son parent
                $menuIndexed[$item['PARENT_ID']]['SUBMENU'][] = &$item;
            } else {
                // Sinon, c'est un élément principal (menu racine)
                $menuTree[$id] = &$item;
            }
        }

        // ajoute à la fin du tableau l'entrée Login
        $menuTree[] = [
            'ID' => 'login',
            'VALUE' => 'Login',
            'PARENT_ID' => null,
            'POSITION' => 'login',
            'SUBMENU' => [],
            'ACTIONS' => [
                'onclick' => 'openModal(\'login\')'
            ],
        ];

        return $menuTree;
    }

    public function __debugInfo()
    {
        return [
            'MenuDatas' => $this->menu
        ];
    } 
}