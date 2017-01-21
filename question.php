<?php

class question
{
    public $id;
    public $intitule;
    public $type;
    public $id_shotgun;

    public static $TYPE_CHOIXMULTIPLE = 0;
    public static $TYPE_CHOIXUNIQUE = 1;
    public static $TYPE_REPONSELIBRE = 2;
   
    public static function insererQuestion($dbh, $id, $intitulé, $choix_multiple, $id_shotgun)
    {
        if (!getQuestion($dbh, $id))
        {
            $sth = $dbh->prepare("INSERT INTO `question` (`id`, `intitulé`, `choix_multiple`, `id_shotgun`) VALUES(?,?,?,?))");
            $sth->execute(array($id, $intitulé, $choix_multiple, $id_shotgun));
        }
        else
        {
            echo("La question existe déjà"); // à insérer en erreur?
        }
    }

    // Récupère les questions du shotgun $idShot
    public static function getQuestions($mysqli, $idShot)
    {
        $a = array();

        // On sélectionne ceux qui ne sont pas ecore publiés à cause de leur date de publi, mais qui sont actifs
        $query = "SELECT * FROM question AS quest WHERE quest.id_shotgun='$idShot' ORDER BY quest.id ASC;";

        $result = $mysqli->query($query);

        if(!$result)
            die($mysqli->error);

        while(($row = $result->fetch_object('question')))
        {
            $a[] = $row;
        }

        return $a;
    }

    public static function getShotgun($dbh, $id)
    { // Donne le shotgun auquel renvoie la question
        $query = "SELECT shotgun_event.* FROM question,shotgun_event WHERE question.id =? AND shotgun_event.id = id_shotgun ";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'question');
        $sth->execute(array($id));
        $user = $sth->fetchAll();
        $sth->closeCursor();
        return $user;
    }

    public static function getQuestion_Rep($dbh, $id)
    { // Donne la question associée à la réponse id
        $query = "SELECT question.* FROM question,reponse WHERE reponse.id =? AND question.id = id_question ";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'question');
        $sth->execute(array($id));
        $user = $sth->fetchAll();
        $sth->closeCursor();
        return $user;
    }

}
