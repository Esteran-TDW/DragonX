<?php

require_once __DIR__ . '/classes/System/System.class.system.php';

$_instance = [];

$System = new System();
$class = $System->autoloadFilesClasses();

foreach ($class as $className) {

    $$className = new $className();
    $_instance[$className]['initialized'] = true;
    $_instance[$className]['sessionId'] = $_SESSION['id'];

}
