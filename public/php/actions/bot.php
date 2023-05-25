<?php
const BOT_URL = "https://discord.com/api/oauth2/authorize?client_id=997885287743111169&permissions=397553036368&scope=bot%20applications.commands";

$bot_url = BOT_URL;

$body = <<<HTML
<h2>Kazooha</h2>
<p>Vous pouvez l'inviter avec <a href="$bot_url">ce lien</a>.</p>
HTML;
