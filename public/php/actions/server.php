<?php
$minecraft_vanilla_server_connection = @fsockopen("jo.narukami-edition.fr", "42957", $errno, $errstr, 0.5);
if ($minecraft_vanilla_server_connection >= 1) {
    $minecraft_vanilla_server_connection = "le serveur est <span style='color: green'>en ligne!</span>";
} else {
    $minecraft_vanilla_server_connection = "le serveur est <span style='color: red'>hors ligne</span>";
}

$minecraft_create_server_connection = @fsockopen("jo.narukami-edition.fr", "999", $errno, $errstr, 0.5);
if ($minecraft_create_server_connection >= 1) {
    $minecraft_create_server_connection = "le serveur est <span style='color: green'>en ligne!</span>";
} else {
    $minecraft_create_server_connection = "le serveur est <span style='color: red'>hors ligne</span>";
}

$terraria_server_connection = @fsockopen("jo.narukami-edition.fr", "4649", $errno, $errstr, 0.5);
if ($terraria_server_connection >= 1) {
    $terraria_server_connection = "le serveur est <span style='color: green'>en ligne!</span>";
} else {
    $terraria_server_connection = "le serveur est <span style='color: red'>hors ligne</span>";
}
$body = <<<HTML
<h3>Serveurs Minecraft</h3>
<p>On a deux serveurs Minecraft:
<ul class="about">
    <li>Un serveur vanilla 1.19.2, disponible à cette adresse IP: <code>jo.narukami-edition.fr:42957</code>. Actuellement, $minecraft_vanilla_server_connection.</li>
    <li>Un serveur Create 1.19.2, disponible à cette adresse IP: <code>jo.narukami-edition.fr:999</code>. Actuellement, $minecraft_create_server_connection. <br>Voici les dépendances:
        <ul class="about">
            <li><a href="https://fabricmc.net/">Fabric</a></li>
            <li><a href="https://www.curseforge.com/minecraft/mc-mods/fabric-api/files/4438705">Fabric API</a></li>
            <li><a href="https://www.curseforge.com/minecraft/mc-mods/create-fabric/files/4537370">Create pour Fabric</a></li>
        </ul>
    </li>
</ul>
</p>
<h3>Serveur Terraria</h3>
<p>On a aussi un serveur Terraria, disponible à cette adresse IP: <code>jo.narukami-edition.fr</code>, et le port <code>4649</code>. Actuellement, $terraria_server_connection</p>
<h4>Si les serveurs sont hors ligne</h4>
<p>Appuyez <a href="/index.php?action=wake">ici.</a></p>
HTML;
