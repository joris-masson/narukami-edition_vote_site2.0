<?php
if (!isset($_SESSION["discord_id"]) || !isset($_SESSION["username"]) || !isset($_SESSION["discriminator"])) {
    $body = "<p>Bonjour, désolé, mais j'ai aucune idée de qui t'es!</p>";
} else {
    $discord_id = $_SESSION["discord_id"];

    $body = "<h2>Hello!</h2>";
    if ($_SESSION["use_new_names"]) {
        $global_name = $_SESSION["global_name"];
        $display_name = $_SESSION["display_name"];
        $body = "Bonjour $global_name($display_name), votre ID discord est $discord_id";
    } else {
        $username = $_SESSION["username"];
        $discriminator = $_SESSION["discriminator"];
        $body = "Bonjour $username#$discriminator, votre ID discord est $discord_id";
    }
}
