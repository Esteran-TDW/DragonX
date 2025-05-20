<?php
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}

function buildMenu($object){
    $menuArray = objectToArray($object);
    return $menuArray['menu'];
}