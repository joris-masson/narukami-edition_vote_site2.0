<?php

use classes\Photo;

function exchange_token($code)
{
    $data = array(
        "client_id" => OAUTH2_CLIENT_ID,
        "client_secret" => OAUTH2_CLIENT_SECRET,
        "grant_type" => "authorization_code",
        "code" => $code,
        "redirect_uri" => REDIRECT_URL . "?action=connect"
    );

    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $result = json_decode(file_get_contents(TOKEN_URL, false, stream_context_create($options)));

    return $result->access_token;
}

function get_user_info($token) {
    $options = array(
        'http' => array(
            'header'  => "Authorization: Bearer $token",
            'method'  => 'GET',
        )
    );
    return json_decode(file_get_contents(USER_API_URL, false, stream_context_create($options)));
}

function get_code() {
    $params = array(
        'client_id' => OAUTH2_CLIENT_ID,
        'redirect_uri' => REDIRECT_URL . "?action=connect",
        'response_type' => 'code',
        'scope' => 'identify'
    );
    // Redirect the user to Discord's authorization page
    header('Location: ' . AUTHORIZE_URL . '?' . http_build_query($params));
    die();
}

/**
 * Récupère une valeur de $_GET
 * @param string $key la clé de la valeur à récupérer
 * @return string|null la valeur associée à la clé, ou null si elle n'existe pas
 */
function get(string $key) {
    return key_exists($key, $_GET) ? trim($_GET[$key]) : null;
}

/**
 * Compte les erreurs dans un array d'erreurs.
 * @param array $errArray un array d'erreurs
 * @return int le nombre d'erreurs
 */
function count_errors(array $errArray): int
{
    $res = count($errArray);
    foreach ($errArray as $value) {
        if (is_null($value)) {
            $res--;
        }
    }
    return $res;
}

/**
 * Se connecte à la base de données, et renvoie sa connexion.
 * @return PDO
 */
function connecter(): PDO
{
    try {
        // Options de connection
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        $connection = new PDO(DATABASE_DNS, DATABASE_USERNAME, DATABASE_PASSWORD, $options);
        return ($connection);


    } catch (Exception $e) {
        echo "Connection à MySQL impossible : ", $e->getMessage();
        die();
    }
}

function construct_photo_list(): string
{
    $res = "";
    $connection = connecter();
    $query = $connection->query("SELECT * FROM Photo");
    $query->setFetchMode(PDO::FETCH_OBJ);
    $index = 0;
    while ($elem = $query->fetch()) {
        // Affichage des enregistrements
        $photo = new Photo($elem->id, $elem->author, $elem->title, $elem->descriptionP, $elem->dateS);
        $res .= $photo->show_vote($index);
        $index++;
    }
    return $res;
}

function get_participants_ids(): array
{
    $connection = connecter();
    $query = $connection->query("SELECT id FROM Photo");
    $query->setFetchMode(PDO::FETCH_OBJ);
    $res = array();
    while ($elem = $query->fetch()) {
        $res[] = $elem->id;
    }
    $connection = null;
    return $res;
}

function my_log(string $log): bool
{
    $log = "[" . get("action") . "] [" . date("Y-m-d H:i:s") . "] [" . get_user_ip() . "] - " . $log;

    $logfile = "admin/logs/";
    if (in_array(get_user_ip(), get_blacklisted_ips())) {
        $logfile .= "ignored";
        $log .= "\n";
        file_put_contents($logfile, $log, FILE_APPEND);
        return false;
    } else if (isset($_SESSION["discord_id"])) {
        $id = $_SESSION["discord_id"];
        $logfile .= "$id";
    } else {
        $logfile .= "common";
    }
    file_put_contents($logfile . ".txt", $log . "\n", FILE_APPEND);
    return true;
}

function get_user_ip()
{
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') > 0) {
            $addr = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($addr[0]);
        } else {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function get_blacklisted_ips(): array
{
    $res = array();
    $connection = connecter();
    $query = $connection->query("SELECT * FROM Kazooha.User WHERE blacklisted=1");
    $query->setFetchMode(PDO::FETCH_OBJ);
    while ($elem = $query->fetch()) {
        $res[] = $elem->ip;
    }
    $connection = null;
    return $res;
}

function set_id_of_adress(string $id, string $adress): void
{
    $connection = connecter();
    $query = $connection->prepare("UPDATE Kazooha.User SET discordId=:id WHERE ip=:ip");
    $query->execute(array(
        ":id" => $id,
        ":ip" => $adress
    ));
    $connection = null;
}

function add_address(string $adress): void
{
    if (!is_in_adresses($adress)) {
        $connection = connecter();
        $query = $connection->prepare("INSERT INTO Kazooha.User (ip) VALUE (:ip)");
        $query->execute(array(
            ":ip" => $adress
        ));
        if (isset($_SESSION["discord_id"])) {
            set_id_of_adress($_SESSION["discord_id"], $adress);
        }
        $connection = null;
    }
}

function is_in_adresses($adress): bool
{
    $res = array();
    $connection = connecter();
    $ip = get_user_ip();
    $query = $connection->query("SELECT * FROM Kazooha.User WHERE ip='$ip'");
    $query->setFetchMode(PDO::FETCH_OBJ);
    return !(count($query->fetchAll()) == 0);
}
