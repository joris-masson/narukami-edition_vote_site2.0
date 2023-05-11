<?php
/**
 * Action à réaliser lors de <code>list</code>: liste toutes les photos de la base de données.
 * @author Joris MASSON
 * @author Le cours
 * @package actions
 * @uses Photo
 */

use classes\Photo;

$body = "<h2>Liste des photos!</h2>";
$connection = connecter();

if (!CAN_SEE_PHOTOS && !isset($_SESSION["discord_id"])) {
    $body = "<h2>Impossible de voir les photos pour l'instant.</h2>";
    $body .= "<p>La visibilité des photos est désactivée pour le moment, si vous voulez voir votre propre photo, il faut vous connecter.</p>";
} else {
    if (CAN_SEE_PHOTOS) {
        $query = $connection->query("SELECT * FROM Photo");
    } else {
        $id = $_SESSION["discord_id"];
        $query = $connection->query("SELECT * FROM Photo WHERE id=$id");  // $_SESSION est server-side only, donc pas de risque
    }

    // On indique que nous utiliserons les résultats en tant qu'objet
    $query->setFetchMode(PDO::FETCH_OBJ);

    // Nous traitons les résultats en boucle
    $body .= "<table><thead><tr><th>Auteur</th><th>Titre</th><th>Actions</th></tr></thead><tbody>";

    while ($elem = $query->fetch()) {
        // Affichage des enregistrements
        $photo = new Photo($elem->id, $elem->author, $elem->title, $elem->descriptionP, $elem->dateS);
        $body .= $photo->show_row();
    }
    $body .= "</tbody></table>";
}