<?php
if (!isset($_SESSION["discord_id"]) || !isset($_SESSION["username"])) {
    $body = "<p>Bonjour, désolé, mais j'ai aucune idée de qui t'es!</p>";
} else {
    $discord_id = $_SESSION["discord_id"];

    $body = "<h2>Hello!</h2>";
    $username = $_SESSION["username"];
    if ($_SESSION["use_new_names"]) {
        $global_name = $_SESSION["global_name"];
        $body = "Bonjour $username($global_name), votre ID discord est $discord_id";
    } else {
        $discriminator = $_SESSION["discriminator"];
        $body = "Bonjour $username#$discriminator, votre ID discord est $discord_id";
    }
}
