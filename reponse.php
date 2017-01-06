<?php

class reponse
{
    public $id;
    public $id_question;
    public $intitule;

    public static function insererReponse($dbh, $id, $id_question, $type, $intitule)
    {
        if(!getReponse($dbh, $id))
        {
            $sth = $dbh->prepare("INSERT INTO `reponse` (`id`, `id_question`, `type`, `intitule`) VALUES(?,?,?,?))");
            $sth->execute(array($id, $id_question, $type, $intitule));
        }
        else
        {
            echo("La réponse existe déjà"); // à insérer en erreur?
        }
    }

    public static function getReponses($mysqli, $idQuest)
    {
        $a = array();

        // On sélectionne ceux qui ne sont pas ecore publiés à cause de leur date de publi, mais qui sont actifs
        $query = "SELECT * FROM reponse AS rep WHERE rep.id_question='$idQuest';";

        $result = $mysqli->query($query);

        if(!$result)
            die($mysqli->error);

        while(($row = $result->fetch_object('reponse')))
        {
            $a[] = $row;
        }

        return $a;
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