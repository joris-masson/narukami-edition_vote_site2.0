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
    private string $id;

    /**
     * @var string $dateS La date de soumission de la photo
     *
     * Format: <code>Y-m-d H:i:s</code>(format TIMESTAMP SQL).
     */
    private string|null $dateS;

    /**
     * @var string $author L'auteur de la photo.
     */
    private string $author;

    /**
     * @var string $title Le titre de la photo(optionnel).
     */
    private string $title;

    /**
     * @var string $descriptionP La description de la photo.
     */
    private string $descriptionP;

    /**
     * @var bool $show_result Est-ce que le score est affiché en public
     */
    private bool $show_result;

    /**
     * @var string URL de l'image de profil
     */
    private string|null $avatar_url;

    /**
     * Constructeur de la photo, initialise les attributs.
     * @param string $id L'ID discord de 'auteur
     * @param string $author L'auteur de la photo
     * @param string $title Le titre de la photo
     * @param string $descriptionP La description de la photo
     * @param bool $show_result Si le résultat peut être montré en public lors de l'annonce des résultats
     * @param string $avatar_url L'URL de l'avatar de la personne soumettant la photo
     * @param string|null $dateS La date de soumission de la photo(<code>Y-m-d H:i:s</code>)
     */
    public function __construct(string $id, string $author, string $title, string $descriptionP, bool $show_result = false, string $avatar_url = "", string $dateS = null)
    {
        $this->id = $id;
        $this->dateS = $dateS;
        $this->author = $author;
        $this->title = $title;
        $this->descriptionP = $descriptionP;
        $this->show_result = $show_result;
        $this->avatar_url = $avatar_url;
    }

    /**
     * Récupère les valeurs d'une photo d'un ID donné.
     * @param string $id l'ID de la photo à récupérer
     * @return array|bool
     */
    public static function fetch_all_values(string $id): array|bool
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
     * Getter pour show_result
     * @return bool show_result
     */
    public function get_show_result(): bool
    {
        return $this->show_result;
    }

    /**
     * Getter pour avatar_url
     * @return string avatar_url
     */
    public function ger_avatar_url(): string
    {
        return $this->avatar_url;
    }

    /**
     * Affichage HTML en ligne de tableau de la photo.
     * @return string HTML
     */
    public function show_row(): string
    {
        if (!isset($_SESSION["discord_id"]) || $this->id != $_SESSION["discord_id"]) {
            return "<tr><td class='author'>$this->author</td><td class='title'><a href='index.php?action=detail&id=$this->id'>$this->title</a></td></tr>";
        } else {
            return "<tr><td class='author'>$this->author</td><td class='title'><a href='index.php?action=detail&id=$this->id'>$this->title</a></td><td class='actions'><a class='delete' href='index.php?action=delete&id=$this->id'>Effacer</a><br><a href='index.php?action=update&id=$this->id'>Mettre à jour</a></td></tr>";
        }
    }

    /**
     * Affichage HTML détaillé de la photo.
     * @return string HTML
     */
    public function show_detail(): string
    {
        $description = $this->markdown_to_html($this->descriptionP);
        if (!isset($_SESSION["discord_id"]) || $this->id != $_SESSION["discord_id"]) {
            return <<<HTML
                <h2>$this->author: <span class="photo_title">$this->title</span></h2>
                <p>Description: $description</p>         
                <img src='https://jo.narukami-edition.fr/public/images/photos/$this->id.png' alt='$this->title'>
                <p>La photo a été soumise le: <code>$this->dateS</code></p>
                HTML;
        } else {
            return <<<HTML
                <h2>$this->author: <span class="photo_title">$this->title</span></h2>
                <p>Description: $description</p>              
                <img src='https://jo.narukami-edition.fr/public/images/photos/$this->id.png' alt='$this->title'>
                <p>La photo a été soumise le: <code>$this->dateS</code></p>
                <a id="update" href="index.php?action=update&id=$this->id">Mettre à jour la photo</a>
                <a class="delete" href="index.php?action=delete&id=$this->id">Supprimer la photo</a>
                HTML;
        }
    }

    /**
     * Affiche la photo pour le vote.
     * @param $index l'index de la photo
     * @return string un affichage pour le vote
     */
    public function show_vote($index): string
    {
        return <<<HTML
                <fieldset class="photo">
                    <label><input name="data[$index][id]" hidden="hidden" value="$this->id" required></label>
                    <h3>$this->author</h3>
                    <h4>$this->title</h4>
                    <p>$this->descriptionP</p>
                    <img src="https://jo.narukami-edition.fr/public/images/photos/$this->id.png" alt="Photo de $this->author">
                    <label>Titre<input name="data[$index][title]" type="number" min="0" max="1" required></label>
                    <label>Description<input name="data[$index][description]" type="number" min="0" max="4" required></label>
                    <label>Photo<input name="data[$index][photo]" type="number" min="0" max="6" required></label>
                </fieldset>
                HTML;

    }

    /**
     * Insert dans la base de données les attributs actuels.
     * @return string l'ID de la photo une fois insérée dans la base de données
     */
    public function insert_to_database(): string
    {
        $connection = connecter();  // on se connecte à la database
        $prep_req = $connection->prepare("INSERT INTO Photo (id, dateS, author, title, descriptionP, showResult, avatarUrl) VALUE (:id, NOW(), :author, :title, :descriptionP, :showResult, :avatarUrl)");
        $prep_req->execute(array(
            ":id" => $this->id,
            ":author" => $this->author,
            ":title" => $this->title,
            ":descriptionP" => $this->descriptionP,
            ":showResult" => $this->show_result ? 1 : 0,
            ":avatarUrl" => $this->avatar_url
        ));
        $this->dateS = date('Y-m-d H:i:s', time());
        $connection = null;  // on ferme la connexion
        move_uploaded_file($_FILES["photo"]["tmp_name"], "public/images/photos/$this->id.png");

        return $this->id;
    }

    /**
     * Met à jour la photo dans la base de données avec les valeurs actuelles.
     */
    public function update_in_database(): void
    {
        $connection = connecter();
        $prep_req = $connection->prepare("UPDATE Photo SET title=:title, descriptionP=:descriptionP, showResult=:showResult WHERE id=:id");
        $prep_req->execute(array(
            ":title" => $this->title,
            ":descriptionP" => $this->descriptionP,
            ":showResult" => $this->show_result ? 1 : 0,
            ":id" => $this->id
        ));
        if (file_exists("public/images/temp/$this->id.png")) {
            rename("public/images/temp/$this->id.png", "public/images/photos/$this->id.png");
        }
        $connection = null;
    }

    /**
     * Méthode permettant de convertir du markdown Discord en HTML, en utilisant une bibli python.
     * @param string $str une string normale avec du markdown Discord dedans
     * @return string une string convertie
     */
    function markdown_to_html(string $str): string
    {
        $cwd = getcwd();
        return shell_exec("python $cwd/public/data/scripts/convert_markdown_to_html.py \"$str\"");
    }
}