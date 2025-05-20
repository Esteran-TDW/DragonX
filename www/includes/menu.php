<?php
 // Empêche l'accès direct au fichier
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}

$Menu = buildMenu($MenuBuilder);
