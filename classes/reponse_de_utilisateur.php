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
            die('Erreur irrécupérable dans insertReponseUtilisateur');

        $stmt->close();
        return true;
    }
}