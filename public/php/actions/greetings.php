<?php
if (!isset($_SESSION["discord_id"]) || !isset($_SESSION["username"]) || !isset($_SESSION["discriminator"])) {
    $body = "<p>Bonjour, désolé, mais j'ai aucune idée de qui t'es!</p>";
} else {
    $discord_id = $_SESSION["discord_id"];
    $username = $_SESSION["username"];
    $discriminator = $_SESSION["discriminator"];

    $body = "<h2>Oui!</h2>";
    $body = "Bonjour $username#$discriminator, votre ID discord est $discord_id";
}
