<?php

session_start();

require_once __DIR__ . '/kernel/classes/System/System.class.system.php';

// Vérification et création des objects
$System = $System ?? new System();
$Security = $Security ?? new Security();
$db = $db ?? new Database();
$Style = $Style ?? new Style();


// Chargement de la structure de la page
require_once(HEADER);
require_once(MAIN);
require_once(FOOTER);

