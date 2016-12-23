<?php

// Sert à implémenter la classe utilisateur reliée à la base de donnée ainsi que toutes les fonctions utilisant la classe
class utilisateur
{
    public $mail;
    public $active;
    public $code_secret;
    public $mdp;
    public $admin;

    public static function insererUtilisateur($dbh, $mail, $active, $code_secret, $mdp, $admin)
    {
        if (!getUtilisateur($dbh, $mail))
        {
            $sth = $dbh->prepare("INSERT INTO `utilisateur` (`mail`, `active`, `code_secret`, `mdp`, `admin`) VALUES(?,?,?,MD5(?),?)");
            $sth->execute(array($mail, $active, $code_secret, $mdp, $admin));
        }
        else
        {
            echo("L'utilisateur existe deja");
        }
    }

    public static function getUtilisateur($dbh, $mail)
    { // renvoie l'utilisateur sous la classe utilisateur s'il existe et false sinon
        $query = "SELECT * FROM `utilisateur` WHERE mail = ?;";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'utilisateur');
        $sth->execute(array($mail));
        $user = $sth->fetch(); // renvoi false si l'uilisateur n'existe pas
        $sth->closeCursor();
        return $user;
    }

    public static function testerMdp($dbh, $user, $mdp)
    {
        return ($user && (md5($mdp) == $user->mdp));
    }

    public static function getShotgun($dbh, $mail)
    { // Donne la liste des shotguns auxquels participe l'utilisateur
        $query = "SELECT shotgun_event.* FROM inscription,shotgun_event WHERE mail_user =? AND shotgun_event.id = id_shotgun ";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'utilisateur');
        $sth->execute(array($mail));
        $user = $sth->fetchAll();
        $sth->closeCursor();
        return $user;
    }

}
