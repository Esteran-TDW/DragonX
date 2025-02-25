<?php
// Empêche l'accès direct
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}

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

    <!-- Inclusion des styles -->
    <?php require_once __DIR__ . '/styles.php'; ?>
</head>
