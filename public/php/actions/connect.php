<?php
if (!isset($_SESSION["discord_id"]) || !isset($_SESSION["username"]) || !isset($_SESSION["discriminator"])) {
    if (get("code") == null) {
        get_code();
    } else {
        $user_info = get_user_info(exchange_token(get("code")));
        $_SESSION["discord_id"] = $user_info->id;
        $_SESSION["username"] = $user_info->username;
        $_SESSION["discriminator"] = $user_info->discriminator;
        header("Refresh:0; url=index.php?action=greetings");
        $body = "<h2>Authentification terminée!</h2>";
    }
}
