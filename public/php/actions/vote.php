<?php
$body = "<h2>Go voter!</h2>";

if (!isset($_SESSION["discord_id"])) {
    $body = "<h2>Il faut être connecté pour voter.</h2>";
}