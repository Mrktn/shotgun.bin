<?php

// Sert à implémenter la classe utilisateur reliée à la base de donnée ainsi que toutes les fonctions utilisant la classe
class utilisateur
{
    public $mail;
    public $active;
    public $code_secret;
    public $mdp;
    public $admin;

    public static function getUtilisateur($mysqli, $mail)
    {
        $query = "SELECT * FROM `utilisateur` WHERE mail = \"" . $mail . "\";";

        $result = $mysqli->query($query);
        $object = $result->fetch_object('utilisateur');
        
        return $object;
    }
    public static function insererUtilisateur($mysqli, $mail, $active, $code_secret, $mdp, $admin)
    {
        if(!utilisateur::getUtilisateur($mysqli, $mail))
        {
            $stmt = $mysqli->prepare("INSERT INTO `utilisateur` (`mail`, `active`, `code_secret`, `mdp`, `admin`) VALUES(?,?,?,MD5(?),?)");
            $stmt->bind_param('sissi', $mail, $active, $code_secret, $mdp, $admin);
            return ($stmt->execute());
        }

        else
        {
            header('Location: index.php?activePage=error&msg=Il y a déjà un utilisateur enregitré avec cette adresse mail !');
        }
    }

    

    public static function testerMdp($mysqli, $user, $mdp)
    {
        return ($user && (md5($mdp) == $user->mdp));
    }

    /*public static function getShotgun($mysqli, $mail)
    { // Donne la liste des shotguns auxquels participe l'utilisateur
        $query = "SELECT shotgun_event.* FROM inscription,shotgun_event WHERE mail_user =? AND shotgun_event.id = id_shotgun ";
        $sth = $mysqli->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'utilisateur');
        $sth->execute(array($mail));
        $user = $sth->fetchAll();
        $sth->closeCursor();
        return $user;
    }*/

}
