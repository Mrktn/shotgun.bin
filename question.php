<?php

class question {

    public $id;
    public $intitulé;
    public $choix_multiple;
    public $id_shotgun;

    public static function insererQuestion($dbh, $id, $intitulé, $choix_multiple, $id_shotgun) {
        if (!getQuestion($dbh, $id)) {
            $sth = $dbh->prepare("INSERT INTO `question` (`id`, `intitulé`, `choix_multiple`, `id_shotgun`) VALUES(?,?,?,?))");
            $sth->execute(array($id, $intitulé, $choix_multiple, $id_shotgun));
        } else {
            echo("La question existe déjà"); // à insérer en erreur?
        }
    }

    public static function getQuestion($dbh, $id) { // renvoie la question sous la classe question si elle existe et false sinon
        $query = "SELECT * FROM `question` WHERE id = ?;";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'question');
        $sth->execute(array($id));
        $question = $sth->fetch(); // renvoi false si la question n'existe pas
        $sth->closeCursor();
        return $question;
    }

    public static function getShotgun($dbh, $id) { // Donne le shotgun auquel renvoie la question
        $query = "SELECT shotgun_event.* FROM question,shotgun_event WHERE question.id =? AND shotgun_event.id = id_shotgun ";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'question');
        $sth->execute(array($id));
        $user = $sth->fetchAll();
        $sth->closeCursor();
        return $user;
    }
 
    
    public static function getQuestion_Rep($dbh, $id) { // Donne la question associée à la réponse id
        $query = "SELECT question.* FROM question,reponse WHERE reponse.id =? AND question.id = id_question ";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'question');
        $sth->execute(array($id));
        $user = $sth->fetchAll();
        $sth->closeCursor();
        return $user;
    }    

}
