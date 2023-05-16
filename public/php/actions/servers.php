<?php
$minecraft_server_connection = @fsockopen("jo.narukami-edition.fr", "42957", $errno, $errstr, 0.5);
if ($minecraft_server_connection >= 1) {
    $minecraft_server_connection = "le serveur est <span style='color: green'>en ligne!</span>";
} else {
    $minecraft_server_connection = "le serveur est <span style='color: red'>hors ligne</span>";
}

$terraria_server_connection = @fsockopen("jo.narukami-edition.fr", "4649", $errno, $errstr, 0.5);
if ($terraria_server_connection >= 1) {
    $terraria_server_connection = "le serveur est <span style='color: green'>en ligne!</span>";
} else {
    $terraria_server_connection = "le serveur est <span style='color: red'>hors ligne</span>";
}
$body = <<<HTML
<h3>Serveur Minecraft</h3>
<p>On a un serveur Minecraft, disponible à cette adresse ip: <code>jo.narukami-edition.fr:42957</code>. Actuellement, $minecraft_server_connection</p>
<h3>Serveur Terraria</h3>
<p>On a aussi un serveur Terraria, disponible à cette adresse ip: <code>jo.narukami-edition.fr</code>, et le port <code>4649</code>. Actuellement, $terraria_server_connection</p>
<h4>Si les serveurs sont hors ligne</h4>
<p>Allez <a href="http://jo.narukami-edition.fr:53134/html/fixe_wake_up.html">ici.</a></p>
HTML;
