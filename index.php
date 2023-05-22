<?php
require_once "admin/php/logins.php";
require_once "config.php";
require_once "public/php/utils.php";
require_once("public/php/classes/Photo.php");

$action = get("action");

/* variables importantes */
$errors = array("descriptionP" => null, "photo" => null);  // pour la gestion des erreurs de formulaire
$author = null;
$title = null;
$descriptionP = null;
$photo = null;

session_start();
if (in_array(get_user_ip(), get_blacklisted_ips())) {
    my_log("Refusé");
    include_once "public/php/actions/teapot.php";
}
my_log("Accès");
add_address(get_user_ip());

if (file_exists("public/php/actions/$action.php")) {
    include_once "public/php/actions/$action.php";
} else {
    $body = "<h2>Bienvenue sur Narukami Edition(le site)!</h2>";
}

include_once "public/php/pages/auth.php";
include_once "public/php/pages/menu.php";
include_once "public/php/pages/squelette.php";
