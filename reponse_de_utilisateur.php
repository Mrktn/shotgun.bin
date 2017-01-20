<?php

class reponse_de_utilisateur
{

    public $id;
    public $id_reponse;
    public $texte;
    public $mail_utilisateur;
    public $id_inscription;

    // Insère une réponse
    // $mailUser est déjà vérifié, il provient de la session
    // $idReponse a déjà été checké moult fois (on a vérifié que ça correspondait bien à une question et que c'était un entier - pas dans cet ordre hein)
    public static function insertReponseUtilisateur($mysqli, $idInscription, $mailUser, $idReponse, $texte)
    {
        $stmt = $mysqli->prepare("INSERT INTO reponse_de_utilisateur (id_reponse, texte, mail_utilisateur, id_inscription) VALUES (?, ?, ?, ?)");

        $stmt->bind_param('issi', $idReponse, $texte, $mailUser, $idInscription);

        return ($stmt->execute());
    }

    public static function getReponseUtilisateur($dbh, $id)
    { // renvoie la réponse de l'utilisateur sous la classe reponse_de_utilisateur si elle existe et false sinon
        $query = "SELECT * FROM `reponse_de_utilisateur` WHERE id = ?;";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'reponse_de_utilisateur');
        $sth->execute(array($id));
        $reponse = $sth->fetch(); // renvoi false si la question n'existe pas
        $sth->closeCursor();
        return $reponse;
    }

    public static function getReponseUtilisateur_Rep($dbh, $id)
    { // Donne la réponse de l'utilisateur à la reponse id
        $query = "SELECT reponse_de_utilisateur.* FROM reponse_de_utilisateur,reponse WHERE reponse.id =? AND reponse.id = id_reponse ";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'reponse_de_utilisateur');
        $sth->execute(array($id));
        $user = $sth->fetchAll();
        $sth->closeCursor();
        return $user;
    }

}
