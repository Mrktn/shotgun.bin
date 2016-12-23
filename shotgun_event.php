<?php

class shotgun_event
{

    public $id;
    public $titre;
    public $description;
    public $date_event;
    public $date_publi;
    public $nb_places;
    public $prix;
    public $mail_crea;
    public $au_nom_de;

    public static function insererShotgun($dbh, $id, $titre, $description, $date_event, $date_publi, $nb_places, $prix, $mail_crea, $au_nom_de)
    { // créer un nouveau shotgun
        if (!getShotgun($dbh, $id))
        {
            $sth = $dbh->prepare("INSERT INTO `shotgun_event` (`id`, `titre`, `description`, `date_event`, `date_publi`, `nb_places`, `prix`, `mail_crea`, `au_nom_de`) VALUES(?,?,?,?,?,?,?,?,?)");
            $sth->execute(array($id, $titre, $description, $date_event, $date_publi, $nb_places, $prix, $mail_crea, $au_nom_de));
        }
        else
        {
            echo("Shotgun déjà existant");
        }
    }

    public static function getShotgun($dbh, $id)
    { // renvoie le shotgun d'id id et faux sinon , fait-on la même avec en paramètre titre?
        $query = "SELECT * FROM `shotgun_event` WHERE id = ?;";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'shotgun_event');
        $sth->execute(array($id));
        $shotgun = $sth->fetch(); // renvoi false si le shotgun n'existe pas
        $sth->closeCursor();
        return $shotgun;
    }

    public static function getParticipants($dbh, $id)
    { // Donne la liste des participants au shotgun #id
        $query = "SELECT utilisateur.* FROM inscription,utilisateur WHERE mail = mail_user AND id_shotgun = ?";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'shotgun_event');
        $sth->execute(array($id));
        $user = $sth->fetchAll();
        $sth->closeCursor();
        return $user;
    }

    public static function getQuestions($dbh, $id)
    { // Donne la liste des questions associées au shotgun #id
        $query = "SELECT question.* FROM question,shotgun_event WHERE id_shotgun = ? AND id_shotgun = shotgun_event.id ";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'shotgun_event');
        $sth->execute(array($id));
        $user = $sth->fetchAll();
        $sth->closeCursor();
        return $user;
    }

}
