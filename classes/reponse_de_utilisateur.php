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

        if(!$stmt || !($stmt->bind_param('issi', $idReponse, $texte, $mailUser, $idInscription)) || !$stmt->execute())
            return false;

        $stmt->close();
        return true;
    }

    public static function getReponseUtilisateur($mysqli, $id)
    { // renvoie la réponse de l'utilisateur sous la classe reponse_de_utilisateur si elle existe et false sinon
        $query = "SELECT * FROM `reponse_de_utilisateur` WHERE id = ?;";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $id);
        if(!$stmt->execute())
        {
            die($stmt->error);
        }
        $result = $stmt->get_result();
        $row = $result->fetch_object('reponse');
        $stmt->close();
        return $row;
    }

    public static function getReponseUtilisateur_Rep($mysqli, $id)
    { // Donne la réponse de l'utilisateur à la reponse id
        $a = array();
        $query = "SELECT reponse_de_utilisateur.* FROM reponse_de_utilisateur,reponse WHERE reponse.id =? AND reponse.id = id_reponse ";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $id);
        if(!$stmt->execute())
        {
            die($stmt->error);
        }
        $result = $stmt->get_result();

        while($row = $result->fetch_object())
        {
            $a[] = $row;
        }
        $stmt->close();
        return $a;
    }

}