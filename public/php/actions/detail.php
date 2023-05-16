<?php
/**
 * Action à réaliser lors de <code>detail</code>: affiche le détail d'une photo.
 * @author Joris MASSON
 * @package actions
 * @uses Photo
 */

use classes\Photo;

header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$id = key_exists('id', $_GET) ? $_GET['id'] : null;

if (!CAN_SEE_PHOTOS && $_SESSION["discord_id"] != $id) {
    $body = "<h2>Impossible de voir le détail des photos qui ne vous appartiennent pas maintenant.</h2>";
} else if (!is_numeric($_GET["id"])) {
    $body = "<h2 class='error'>Erreur, id incorrect.</h2>";
} else {
    $connection = connecter();

    $prep_req = $connection->prepare("SELECT * FROM Photo WHERE id=:id");
    $prep_req->execute(array(':id' => $id));
    $data_photo = $prep_req->fetch();

    $photo = new Photo($data_photo["id"], $data_photo["author"], $data_photo["title"], $data_photo["descriptionP"], "", "", $data_photo["dateS"]);

    $body = $photo->show_detail();  // demande le détail de la photo et l'affiche

    $query = null;
    $connection = null;
}
