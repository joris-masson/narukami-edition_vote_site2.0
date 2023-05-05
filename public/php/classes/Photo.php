<?php

namespace classes;

/**
 * Classe principale représentant les photos!
 * @author Joris MASSON
 * @package classes
 */
class Photo
{
    /**
     * @var string $id L'ID de la photo.
     */
    private $id;

    /**
     * @var string $dateS La date de soumission de la photo
     *
     * Format: <code>Y-m-d H:i:s</code>(format TIMESTAMP SQL).
     */
    private $dateS;

    /**
     * @var string $author L'auteur de la photo.
     */
    private $author;

    /**
     * @var string $title Le titre de la photo(optionnel).
     */
    private $title;

    /**
     * @var string $descriptionP La description de la photo.
     */
    private $descriptionP;

    /**
     * Constructeur de la photo, initialise les attributs.
     * @param string $id L'ID discord de 'auteur
     * @param string $author L'auteur de la photo
     * @param string $title Le titre de la photo
     * @param string $descriptionP La description de la photo
     * @param string|null $dateS La date de soumission de la photo(<code>Y-m-d H:i:s</code>)
     */
    public function __construct(string $id, string $author, string $title, string $descriptionP, string $dateS = null)
    {
        $this->id = $id;
        $this->dateS = $dateS;
        $this->author = $author;
        $this->title = $title;
        $this->descriptionP = $descriptionP;
    }

    /**
     * Récupère les valeurs d'une photo d'un ID donné.
     * @param int $id l'ID de la photo à récupérer
     * @return array
     */
    public static function fetch_all_values(int $id): array
    {
        $connection = connecter();

        $prep_req = $connection->prepare("SELECT * FROM Photo WHERE id=:id");
        $prep_req->execute(array(':id' => $id));
        $connection = null;

        return $prep_req->fetch();
    }

    /**
     * Supprime la photo d'ID donné.
     * @param string $id L'ID de la photo à supprimer
     * @return void
     */
    public static function delete_from_database(string $id): void
    {
        $connection = connecter();
        $prep_req = $connection->prepare("DELETE FROM Photo WHERE id=:ip");
        $prep_req->execute(array(':ip' => $id));
        unlink("public/images/photos/$id.png");

        $connection = null;
    }

    /**
     * Getter pour id
     * @return string id
     */
    public function get_id(): string
    {
        return $this->id;
    }

    /**
     * Getter pour dateS
     * @return string dateS
     */
    public function get_dateS(): string
    {
        return $this->dateS;
    }

    /**
     * Getter pour author
     * @return string author
     */
    public function get_author(): string
    {
        return $this->author;
    }

    /**
     * Getter pour title
     * @return string title
     */
    public function get_title(): string
    {
        return $this->title;
    }

    /**
     * Getter pour descriptionP
     * @return string descriptionP
     */
    public function get_descriptionP(): string
    {
        return $this->descriptionP;
    }

    /**
     * Affichage HTML en ligne de tableau de la photo.
     * @return string HTML
     */
    public function show_row(): string
    {
        if ($this->id != $_SESSION["discord_id"]) {
            return "<tr><td class='author'>$this->author</td><td class='title'><a href='index.php?action=detail&id=$this->id'>$this->title</a></td></tr>";
        } else {
            return "<tr><td class='author'>$this->author</td><td class='title'><a href='index.php?action=detail&id=$this->id'>$this->title</a></td><td class='actions'><a class='delete' href='index.php?action=delete&id=$this->id'>Effacer</a><a href='index.php?action=update&id=$this->id'>Mettre à jour</a></td></tr>";
        }
    }

    /**
     * Affichage HTML détaillé de la photo.
     * @return string HTML
     */
    public function show_detail(): string
    {
        if ($this->id != $_SESSION["discord_id"]) {
            return <<<HTML
                <h2>$this->author: <span class="photo_title">$this->title</span></h2>
                <p>Description: $this->descriptionP</p>              
                <img src='public/images/photos/$this->id.png' alt='$this->title'>
                <p>La photo a été soumise le: <code>$this->dateS</code></p>
                HTML;
        } else {
            return <<<HTML
                <h2>$this->author: <span class="photo_title">$this->title</span></h2>
                <p>Description: $this->descriptionP</p>              
                <img src='public/images/photos/$this->id.png' alt='$this->title'>
                <p>La photo a été soumise le: <code>$this->dateS</code></p>
                <a id="update" href="index.php?action=update&id=$this->id">Mettre à jour la photo</a>
                <a class="delete" href="index.php?action=delete&id=$this->id">Supprimer la photo</a>
                HTML;
        }
    }

    /**
     * Insert dans la base de données les attributs actuels.
     * @return string l'ID de la photo une fois insérée dans la base de données
     */
    public function insert_to_database(): string
    {
        $connection = connecter();  // on se connecte à la database
        $prep_req = $connection->prepare("INSERT INTO Photo (id, dateS, author, title, descriptionP) VALUE (:id, NOW(), :author, :title, :descriptionP)");
        $prep_req->execute(array(
            ":id" => $this->id,
            ":author" => $this->author,
            ":title" => $this->title,
            ":descriptionP" => $this->descriptionP,
        ));
        $this->dateS = date('Y-m-d H:i:s', time());
        $connection = null;  // on ferme la connexion
        move_uploaded_file($_FILES["photo"]["tmp_name"], "public/images/photos/$this->id.png");

        return $this->id;
    }

    /**
     * Met à jour la photo dans la base de données avec les valeurs actuelles.
     * @return void
     */
    public function update_in_database(): void
    {
        $connection = connecter();
        $prep_req = $connection->prepare("UPDATE Photo SET title=:title, descriptionP=:descriptionP WHERE id=:id");
        $prep_req->execute(array(
            ":title" => $this->title,
            ":descriptionP" => $this->descriptionP,
            ":id" => $this->id
        ));
        if (file_exists("public/images/temp/$this->id.png")) {
            rename("public/images/temp/$this->id.png", "public/images/photos/$this->id.png");
        }
        $connection = null;
    }
}