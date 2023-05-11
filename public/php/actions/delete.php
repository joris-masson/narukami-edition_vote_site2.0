<?php
/**
 * Action à réaliser lors de <code>delete</code>: prépare la suppression d'une photo en demandant confirmation.
 * @author Joris MASSON
 * @package actions
 */

$id = key_exists('id', $_GET) ? $_GET['id'] : null;

if (IS_VOTING_TIME) {
    $body = "<h2>Les votes sont en cours, impossible de supprimer la photo</h2>";
} else if (!isset($_SESSION["discord_id"])) {
    $body = "<h2>Impossible de supprimer une photo sans être connecté.</h2>";
} else if (!is_numeric($_GET["id"])) {
    $body = "<h2 class='error'>Erreur, id incorrect.</h2>";
} else if ($id != $_SESSION["discord_id"]) {
    $body = "<h2>Vous n'avez pas l'autorisation de supprimer cette photo</h2>";
} else {
    $body = <<<HTML
                <form action='index.php?action=confirm' method='post'>
                <input type='hidden' name='type' value='confirmdelete'/>
                <input type='hidden' name='id' value='$id'/>
                Etes vous sûr de vouloir supprimer cette photo ?
                <p><input class="confirm-button" type='submit' value='delete'><a href='index.php?action=list'>Annuler</a></p>
                </form>
                HTML;
}
