<?php
// Empêche l'accès direct
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}

// Inclusion des classes CSS de base
$css = $System->autoloadCSS();

// Définir le thème de base
$themeCSS = 'default';

try {
   

    // Appelle la superglobale $Style
    
    if (!isset($Style)) {
            throw new Exception('Style object not found.');
        }

   $cssLinks = $Style->getCSSLinks();

    // Parcourir et afficher les balises
    foreach ($cssLinks as $link) {
        echo $link . "\n";
    }
} catch (Exception $e) {
    die('Erreur lors du chargement des CSS : ' . $e->getMessage());
}
