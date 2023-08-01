<?php
/**
 * Action à réaliser lors de <code>add</code>: ajoute une photo à la base de données.
 * @author Joris MASSON
 * @package actions
 * @uses Photo
 */

include_once "public/php/utils.php";

use classes\Photo;

$body = "<h2>Ajoutons une photo!</h2>";

if (IS_VOTING_TIME) {
    $body = "<h2>Les votes sont en cours, impossible d'ajouter une photo</h2>";
} else if (!isset($_SESSION["discord_id"])) {
    $body = "<h2>Il faut être connecté pour ajouter une photo.</h2>";
} else if (in_array($_SESSION["discord_id"], get_participants_ids())) {
    $id = $_SESSION["discord_id"];
    $body = "<h2>Vous avez déjà une <a href='index.php?action=detail&id=$id'>photo</a> de soumise</h2>";
    $body .= "<p>Voulez-vous la <a class='delete' href='index.php?action=delete&id=$id'>supprimer</a> ou la <a href='index.php?action=update&id=$id'>modifier</a>?";
} else if (!isset($_POST["descriptionP"]) && !isset($_FILES["photo"])) { // montre le formulaire si les variables ne sont pas définies dans la requête POST
    include_once("public/php/pages/formulaire.php");
} else {
    $file = $_FILES["photo"];  // récupération de la photo

    $photo = new Photo(
        $_SESSION["discord_id"],
        $_SESSION["username"],
        !empty($_POST["title"]) ? $_POST['title'] : "Sans titre",
        trim($_POST['descriptionP']),
        $_POST["show_result"] === "1",
        $_SESSION["avatar_url"]
    );

    $errors = check_errors_add($photo->get_descriptionP(), $file);

    if (count_errors($errors) == 0) {  // s'il n'y a pas d'erreurs
        $id = $photo->insert_to_database();
        $body .= "<h2>Photo ajoutée!</h2>";
    } else {
        include_once("public/php/pages/formulaire.php");
    }
}

/**
 * Check les erreurs venant des input du formulaire, pour l'action <code>add</code>.
 * @param string $descriptionP Valeur pour <code>descriptionP</code>
 * @param array $file La photo
 * @return array La liste des erreurs reconnues.
 */
function check_errors_add(string $descriptionP, array $file): array
{
    $errors = array( "descriptionP" => null, "photo" => null);  // pour la gestion des erreurs de formulaire
    if ($descriptionP == "") $errors["descriptionP"] = "Il manque une description à la photo";
    if ($file["error"] == 4) $errors["photo"] = "Il manque le plus important: la photo!";  // code erreur 4 -> pas de fichier
    if ($file["error"] == 1) $errors["photo"] = "La photo dépasse la taille maximale autorisée(20M)";
    return $errors;
}
