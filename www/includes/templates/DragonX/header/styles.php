<?php
// Empêche l'accès direct
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}

// Définir le thème de base
$themeCSS = 'default';

try {
    // Créer une instance de Style
    // Récupérer toutes les balises <link>
    $cssLinks = $Style->getCSSLinks();

    // Parcourir et afficher les balises
    foreach ($cssLinks as $link) {
        echo $link . "\n";
    }
} catch (Exception $e) {
    die('Erreur lors du chargement des CSS : ' . $e->getMessage());
}
