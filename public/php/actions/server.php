<?php
$minecraft_hostname = "mc.narukami-edition.fr";
$minecraft_create_hostname = "mc-create.narukami-edition.fr";
$terraria_hostname = "terraria.narukami-edition.fr";

$minecraft_vanilla_server_connection = @fsockopen($minecraft_hostname, "-1", $errno, $errstr, 0.5);
if ($minecraft_vanilla_server_connection >= 1) {
    $minecraft_vanilla_server_connection = "le serveur est <span style='color: green'>en ligne!</span>";
} else {
    $minecraft_vanilla_server_connection = "le serveur est <span style='color: red'>hors ligne</span>";
}

$minecraft_create_server_connection = @fsockopen($minecraft_create_hostname, "-1", $errno, $errstr, 0.5);
if ($minecraft_create_server_connection >= 1) {
    $minecraft_create_server_connection = "le serveur est <span style='color: green'>en ligne!</span>";
} else {
    $minecraft_create_server_connection = "le serveur est <span style='color: red'>hors ligne</span>";
}

$terraria_server_connection = @fsockopen($terraria_hostname, "-1", $errno, $errstr, 0.5);
if ($terraria_server_connection >= 1) {
    $terraria_server_connection = "le serveur est <span style='color: green'>en ligne!</span>";
} else {
    $terraria_server_connection = "le serveur est <span style='color: red'>hors ligne</span>";
}
$body = <<<HTML
<h3>Serveurs Minecraft</h3>
<p>On a deux serveurs Minecraft:
<ul class="about">
    <li>Un serveur vanilla 1.20.4, disponible à cette adresse: <code>$minecraft_hostname</code>. Actuellement, $minecraft_vanilla_server_connection.</li>
    <li>Un serveur Create: Above and Beyond 1.16.5, disponible à cette adresse: <code>$minecraft_create_hostname</code>. Actuellement, $minecraft_create_server_connection.
        <br>
        <p>Le modpack est disponible <a href="https://www.curseforge.com/minecraft/modpacks/create-above-and-beyond">ici</a>.</p>
    </li>
</ul>
</p>
<h3>Serveur Terraria</h3>
<p>On a aussi un serveur Terraria, disponible à cette adresse: <code>$terraria_hostname</code>. Actuellement, $terraria_server_connection</p>
<h4>Si les serveurs sont hors ligne</h4>
<p>Appuyez <a href="/index.php?action=wake">ici.</a></p>
HTML;
