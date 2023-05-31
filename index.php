<?php
/**
 * Coeur du site, servant à décider de quoi afficher via la variable <code>action</code>.
 * @author Joris MASSON
 */
require_once "admin/php/logins.php";
require_once "config.php";
require_once "public/php/utils.php";
require_once "public/php/classes/Photo.php";

$action = get("action");

/* variables importantes */
$errors = array("descriptionP" => null, "photo" => null);  // pour la gestion des erreurs de formulaire
$author = null;
$title = null;
$descriptionP = null;
$photo = null;

session_start();
if (!my_log("Accès")) { // si la personne est blacklistée, renvoi d'un 418.
    include_once "public/php/actions/teapot.php";
}
add_address(get_user_ip());  // stocke l'adresse IP de l'utilisateur en base de données

/* Décision de la page à afficher */
if (is_action_correct($action)) {
    include_once "public/php/actions/$action.php";
} else {
    $body = "<h2>Bienvenue sur Narukami Edition(le site)!</h2>";
}

include_once "public/php/pages/auth.php";
include_once "public/php/pages/menu.php";
include_once "public/php/pages/squelette.php";
