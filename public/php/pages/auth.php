<?php
if (!isset($_SESSION["discord_id"]) || !isset($_SESSION["username"]) || !isset($_SESSION["discriminator"])) {
    $auth = "Vous n'êtes pas authentifié, veuillez <a href='/index.php?action=connect'>vous connecter</a>.";
} else {
    $auth = "Vous êtes authentifié en tant que " . $_SESSION["username"] . '#' . $_SESSION["discriminator"] . ". <a href='/index.php?action=disconnect'>Se déconnecter?</a>";
}