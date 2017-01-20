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
    
    // Vérifie que la réponse n°nr est bien associée à la question n°nq
    public static function repIsValid($mysqli, $nq, $nr)
    {
        // J'ai déjà vérifié avant chaque appel que $nq et $nr sont des entiers avec ctype !!!
        $query = "SELECT * FROM reponse AS rep WHERE rep.id='$nr' AND rep.id_question='$nq';";
        
        $result = $mysqli->query($query);

        if(!$result)
            die($mysqli->error);

        return ($result->num_rows != 0);
    }


    

}