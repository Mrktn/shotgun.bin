<?php

class reponse_de_utilisateur {
    
    public $id;
    public $id_reponse;
    public $texte;
    
    public static function insererReponseUtilisateur($dbh, $id, $id_reponse, $texte) {
        if (!getReponseUtilisateur($dbh, $id)) {
            $sth = $dbh->prepare("INSERT INTO `reponse_de_utilisateur` (`id`, `id_reponse`, `texte`) VALUES(?,?,?))");
            $sth->execute(array($id, $id_reponse, $texte));
        } else {
            echo("La réponse a déjà été enregistrée"); // à insérer en erreur?
        }
    }

    public static function getReponseUtilisateur($dbh, $id) { // renvoie la réponse de l'utilisateur sous la classe reponse_de_utilisateur si elle existe et false sinon
        $query = "SELECT * FROM `reponse_de_utilisateur` WHERE id = ?;";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'reponse_de_utilisateur');
        $sth->execute(array($id));
        $reponse = $sth->fetch(); // renvoi false si la question n'existe pas
        $sth->closeCursor();
        return $reponse;
    }

    public static function getReponseUtilisateur_Rep($dbh, $id) { // Donne la réponse de l'utilisateur à la reponse id
        $query = "SELECT reponse_de_utilisateur.* FROM reponse_de_utilisateur,reponse WHERE reponse.id =? AND reponse.id = id_reponse ";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'reponse_de_utilisateur');
        $sth->execute(array($id));
        $user = $sth->fetchAll();
        $sth->closeCursor();
        return $user;
    }

}

