<?php
/**
 * Action à réaliser lors de <code>update</code>: affiche le formulaire pour update une photo dans la base de données.
 * @author Joris MASSON
 * @package actions
 * @uses Photo
 */

use classes\Photo;

$id = key_exists('id', $_GET) ? $_GET['id'] : null;

if (IS_VOTING_TIME) {
    $body = "<h2>Les votes sont en cours, impossible de modifier la photo pour le moment.</h2>";
} else if (!isset($_SESSION["discord_id"])) {
    $body = "<h2>Impossible de modifier une photo sans être connecté.</h2>";
} else if (!is_numeric($_GET["id"])) {
    $body = "<h2 class='error'>Erreur, ID incorrect.</h2>";
} else if ($id != $_SESSION["discord_id"]) {
    $body = "<h2>Vous n'avez pas l'autorisation de modifier cette photo.</h2>";
} else {
    $body = "<h2>Mise à jour de la photo $id</h2>";
    if (!isset($_FILES["photo"]) && file_exists("public/images/temp/$id.png")) {
        unlink("public/images/temp/$id.png"); // vide le temp
    }
    if (!isset($_POST["author"]) && !isset($_POST["descriptionP"])) {
        $data_photo = Photo::fetch_all_values($id);

        $title = $data_photo["title"];
        $descriptionP = $data_photo["descriptionP"];
        include_once("public/php/pages/formulaire_update.php");
    } else {
        /* Attribution des variables */
        $author = $_SESSION["username"];
        $title = !empty($_POST["title"]) ? str_replace("'", "&#39;", $_POST['title']) : "Sans titre";
        $descriptionP = str_replace("'", "&#39;", trim($_POST['descriptionP']));
        $show_result = isset($_POST["show_result"]);


        $errors = check_errors_update($descriptionP);

        if (count_errors($errors) == 0) {
            if (isset($_FILES["photo"])) {
                $file = $_FILES["photo"];
                move_uploaded_file($file["tmp_name"], "public/images/temp/$id.png");
            }
            $body = <<<HTML
            <form action='index.php?action=confirm' method='post'>
            <input type='hidden' name='type' value='confirmupdate'/>
            <input type='hidden' name='id' value='$id'/>
            <input type='hidden' name='author' value=''/>
            <input type='hidden' name='title' value='$title'/>
            <input type='hidden' name='descriptionP' value='$descriptionP'/>
            <input type="hidden" name="show_result" value="$show_result">
            Etes vous sûr de vouloir mettre à jour cette photo ?
            <p><input class="confirm-button" type='submit' value='Mettre à jour'><a href='index.php?action=list'>Annuler</a></p>
            </form>
            HTML;
        } else {
            include_once("public/php/pages/formulaire_update.php");
        }
    }
}

/**
 * Check les erreurs venant des input du formulaire, pour l'action <code>confirm</code>.
 *
 * Plus précisément pour <code>confirmupdate</delete>.
 * @param string $descriptionP Valeur pour <code>descriptionP</code>
 * @return array La liste des erreurs reconnues.
 */
function check_errors_update(string $descriptionP): array
{
    $errors = array("descriptionP" => null);
    if ($descriptionP == "") $errors["descriptionP"] = "Il manque une description à la photo";

    return $errors;
}
