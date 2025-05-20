<?php
// Empêche l'accès direct
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}

// Inclusion du head
require_once __DIR__ . '/head.php';
?>

<body>
    <header>
        <nav>
            <?php require_once __DIR__ . '/menu.php'; ?>
        </nav>
    </header>
