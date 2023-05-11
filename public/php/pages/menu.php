<?php
$menu = "<li class='menu'><a href='/index.php?action=greetings'>Bonjour</a></li>";
if (isset($_SESSION["discord_id"])) {
    if (IS_VOTING_TIME) {
        $menu .= "<li class='menu'><a href='/index.php?action=vote'>Voter</a></li>";
    } else {
        $menu .= "<li class='menu'><a href='/index.php?action=add'>Ajouter une photo</a></li>";
        if (CAN_SEE_RESULTS) {
            $menu .= "<li class='menu'><a href='/index.php?action=results'>Voir les résultats</a></li>";
        }
    }
}
$menu .= "<li class='menu'><a href='/index.php?action=list'>Liste des photos</a></li>";
$menu .= "<li class='menu'><a href='/index.php?action=about'>À propos</a></li>";
