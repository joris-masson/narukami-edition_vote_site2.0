<?php
require_once "admin/php/logins.php";
require_once "public/php/utils.php";
require_once("public/php/classes/Photo.php");

const AUTHORIZE_URL = "https://discord.com/api/oauth2/authorize";
const TOKEN_URL = "https://discord.com/api/oauth2/token";
const USER_API_URL = "https://discordapp.com/api/users/@me";

$action = get("action");

/* variables importantes */
$errors = array("descriptionP" => null, "photo" => null);  // pour la gestion des erreurs de formulaire
$author = null;
$title = null;
$descriptionP = null;
$dateP = null;
$photo = null;

session_start();

switch ($action) {
    case "add":
        include_once "public/php/actions/add.php";
        break;
    case "list":
        include_once "public/php/actions/list.php";
        break;
    case "delete":
        include_once "public/php/actions/delete.php";
        break;
    case "update":
        include_once "public/php/actions/update.php";
        break;
    case "confirm":
        include_once "public/php/actions/confirm.php";
        break;
    case "detail":
        include_once "public/php/actions/detail.php";
        break;
    case "greetings":
        include_once "public/php/actions/greetings.php";
        break;
    case "connect":
        include_once "public/php/actions/connect.php";
        break;
    case "disconnect":
        include_once "public/php/actions/disconnect.php";
        break;
    default:
        $body = "<h2>Ceci est une action par défaut</h2>";
        break;
}

include_once "public/php/pages/auth.php";
include_once "public/php/pages/menu.php";
include_once "public/php/pages/squelette.php";
