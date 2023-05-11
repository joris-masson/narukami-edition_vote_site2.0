<?php

use classes\Photo;

$res = calc_results();
$body = "<h2>Résultats!</h2>";

foreach ($res as $id => $value) {
    $data_photo = Photo::fetch_all_values($id);
    $author = $data_photo["author"];
    $avatar_url = $data_photo["avatarUrl"];
    $body .= "<h3>$author</h3>";
    $body .= "<img src='$avatar_url' alt='Image de profil de $author'>";
    $body .= "<p>Avec $value points!</p>";
}

function init_res_array(): array
{
    $connection = connecter();
    $query = $connection->query("SELECT id, showResult FROM Photo");
    $query->setFetchMode(PDO::FETCH_OBJ);
    $res = array();
    while ($elem = $query->fetch()) {
        if ($elem->showResult == 1) {
            $res[$elem->id] = 0;
        }
    }
    $connection = null;
    return $res;
}

function calc_results(): array
{
    $vote_files = array_diff(scandir("public/data/votes/"), array('..', '.'));
    $res = init_res_array();
    foreach ($vote_files as &$vote_file) {
        $file = fopen("public/data/votes/$vote_file", 'r');
        while (($line = fgets($file)) !== false) {
            if (array_key_exists(trim(preg_replace("/=[0-9]+$/", "", $line)), $res)) {
                $res[trim(preg_replace("/=[0-9]+$/", "", $line))] += intval(preg_replace("/^[0-9]{18}=/", "", $line));
            }
        }
        fclose($file);
    }
    return $res;
}
