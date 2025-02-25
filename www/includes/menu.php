<?php
// Empêche l'accès direct au fichier
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}

// Configuration de la table et des colonnes
$table = 'menu';
$columns = '*';
$params = [
    'order_by' => [
        'ID',          // Tri par `ID`
        'POSITION ASC' // Tri par `POSITION` en ordre croissant
    ]
];

// Récupération des menus depuis la base de données
$menu = $db->select($columns, $table, $params);

// Organisation des données en menus principaux et sous-menus
$menuTree = [];


foreach ($menu as $item) {
    if (empty($item['PARENT_ID'])) {
        // Menu principal (pas de parent)
        $menuTree[$item['ID']] = $item;
    }

    if (($item['PARENT_ID'] != NULL)) {
        // Menu principal (pas de parent)
        $menuTree[$item['PARENT_ID']]['SUBMENU'][] = $item;
    }

}

?>

<!-- Structure HTML pour afficher les menus -->
<div id="menu_container">
    <ul id="logo">
        <!-- <li><img src="assets/img/logos/main/logo.png"></li> -->
    </ul>
    <ul id="menu">
        <?php
        // Parcourir tous les éléments principaux du menu
        foreach ($menuTree as $mainMenu) {
            echo '<li class="menu-item">';

            // Affichage du menu principal
            echo '<a href="' . htmlspecialchars($mainMenu['LINK'] ?? '#') . '" class="main-menu">' . htmlspecialchars($mainMenu['VALUE']) . '</a>';

            // Vérifier et afficher les sous-menus s'ils existent
            if (!empty($mainMenu['SUBMENU'])) {
                echo '<ul class="submenu">';
                foreach ($mainMenu['SUBMENU'] as $subMenu) {
                    echo '<li><a href="' . htmlspecialchars($subMenu['LINK'] ?? '#') . '" class="sub-menu">' . htmlspecialchars($subMenu['VALUE']) . '</a></li>';
                }
                echo '</ul>'; // Fin des sous-menus
            }

            echo '</li>'; // Fin du menu principal
        }
        ?>
    </ul>
</div>