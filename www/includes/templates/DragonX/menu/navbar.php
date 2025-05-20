<?php
// Empêche l'accès direct au fichier
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}
?>

<!-- Structure HTML pour afficher les menus -->
<div id="menu_container">
    <ul id="logo">
        <!-- <li><img src="assets/img/logos/main/logo.png"></li> -->
    </ul>
    <ul id="menu">
        <?php
        foreach ($Menu as $mainMenu) {
            echo '<li class="menu-item">';

            // Début du lien ou du bouton
            $attributes = '';

            // Si des actions (onclick, onmouseover...) sont définies
            if (!empty($mainMenu['ACTIONS']) && is_array($mainMenu['ACTIONS'])) {
                foreach ($mainMenu['ACTIONS'] as $event => $action) {
                    $attributes .= ' ' . htmlspecialchars($event) . '="' . htmlspecialchars($action) . '"';
                }
            }

            // Affichage du lien ou du bouton principal
            if (!empty($mainMenu['LINK'])) {
                // C'est un lien
                echo '<a href="' . htmlspecialchars($mainMenu['LINK']) . '" class="main-menu"' . $attributes . '>' . htmlspecialchars($mainMenu['VALUE']) . '</a>';
            } else {
                // Pas de lien -> on fait un bouton ou un faux lien avec juste des actions
                echo '<span class="main-menu"' . $attributes . '>' . htmlspecialchars($mainMenu['VALUE']) . '</span>';
            }

            // Sous-menus s'il y en a
            if (!empty($mainMenu['SUBMENU']) && is_array($mainMenu['SUBMENU'])) {
                echo '<ul class="submenu">';
                foreach ($mainMenu['SUBMENU'] as $subMenu) {
                    echo '<li>';

                    $subAttributes = '';

                    if (!empty($subMenu['ACTIONS']) && is_array($subMenu['ACTIONS'])) {
                        foreach ($subMenu['ACTIONS'] as $event => $action) {
                            $subAttributes .= ' ' . htmlspecialchars($event) . '="' . htmlspecialchars($action) . '"';
                        }
                    }

                    if (!empty($subMenu['LINK'])) {
                        echo '<a href="' . htmlspecialchars($subMenu['LINK']) . '" class="sub-menu"' . $subAttributes . '>' . htmlspecialchars($subMenu['VALUE']) . '</a>';
                    } else {
                        echo '<a href="#" class="sub-menu"' . $subAttributes . '>' . htmlspecialchars($subMenu['VALUE']) . '</a>';
                    }

                    echo '</li>';
                }
                echo '</ul>';
            }

            echo '</li>';
        }
        ?>
    </ul>
</div>
