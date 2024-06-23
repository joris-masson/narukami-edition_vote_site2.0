<?php
$body = "<h2>Go voter!</h2>";
$body .= "<p>Le barème est le suivant: <ul class='about'><li>Titre(point bonus) -> 1 point maximum</li><li>Description -> 4 points maximum</li><li>Photo -> 6 points maximum</li></ul></p>";

if (!IS_VOTING_TIME) {
    $body = "<h2>Ce n'est pas le moment de voter.</h2>";
} else if (!isset($_SESSION["discord_id"])) {
    $body = "<h2>Il faut être connecté pour voter.</h2>";
} else if (!isset($_POST["data"])) {
    include_once "public/php/pages/formulaire_vote.php";
} else {
    $res = "";
    foreach ($_POST["data"] as &$participant) {
        $id = $participant["id"];
        $note = intval($participant["title"]) + intval($participant["description"]) + intval($participant["photo"]);
        $res .= "$id=$note\n";
    }
    $id = $_SESSION["discord_id"];
    file_put_contents("./public/data/votes/$id.txt", $res);
    header("Location: https://www.youtube.com/watch?v=YPN0qhSyWy8");
    die();
}