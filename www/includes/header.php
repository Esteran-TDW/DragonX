<?php
// Empêche l'accès direct au fichier
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}
//require_once $Templates->getTemplatePath().'/header/header.php';



$themeCSS = 'default';

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Description de votre site">
    <meta name="author" content="Votre nom ou organisation">
    <link rel="icon" href="<?php echo IMG; ?>/favicon.ico" type="image/x-icon">

    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'DraconisCRM'; ?></title>

    <!-- Stylesheets -->
    <?php
    // Définir  le thème de base
    

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
    ?>
</head>

<body>
    <header>
        <nav>
            <?php require_once(MENU); ?>
        </nav>
    </header>