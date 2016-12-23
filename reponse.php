<?php

class reponse
{

    public $id;
    public $id_question;
    public $type;
    public $intitule;

    public static function insererReponse($dbh, $id, $id_question, $type, $intitule)
    {
        if (!getReponse($dbh, $id))
        {
            $sth = $dbh->prepare("INSERT INTO `reponse` (`id`, `id_question`, `type`, `intitule`) VALUES(?,?,?,?))");
            $sth->execute(array($id, $id_question, $type, $intitule));
        }
        else
        {
            echo("La réponse existe déjà"); // à insérer en erreur?
        }
    }

    public static function getReponse($dbh, $id)
    { // renvoie la réponse sous la classe reponse si elle existe et false sinon
        $query = "SELECT * FROM `reponse` WHERE id = ?;";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'reponse');
        $sth->execute(array($id));
        $reponse = $sth->fetch(); // renvoi false si la question n'existe pas
        $sth->closeCursor();
        return $reponse;
    }

    public static function getReponse_Quest($dbh, $id)
    { // Donne les réponses possibles à la question id 
        $query = "SELECT reponse.* FROM question,reponse WHERE question.id =? AND question.id = id_question ";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'reponse');
        $sth->execute(array($id));
        $user = $sth->fetchAll();
        $sth->closeCursor();
        return $user;
    }

    public static function getReponse_RepUtilisateur($dbh, $id)
    { // Donne la réponse associée à la reponse_de_utilisateur id
        $query = "SELECT reponse.* FROM reponse_de_utilisateur,reponse WHERE reponse_de_utilisateur.id =? AND reponse.id = id_reponse ";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'reponse');
        $sth->execute(array($id));
        $user = $sth->fetchAll();
        $sth->closeCursor();
        return $user;
    }

}
