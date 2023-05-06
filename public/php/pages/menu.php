<?php
$menu = "<li class='menu'><a href='/index.php?action=greetings'>Bonjour</a></li>";
if (isset($_SESSION["discord_id"])) {
    $menu .= "<li class='menu'><a href='/index.php?action=add'>Ajouter une photo</a></li>";
    $menu .= "<li class='menu'><a href='/index.php?action=vote'>Voter</a></li>";
}
$menu .= "<li class='menu'><a href='/index.php?action=list'>Liste des photos</a></li>";
$menu .= "<li class='menu'><a href='/index.php?action=about'>À propos</a></li>";
