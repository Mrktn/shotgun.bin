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

            if(!$stmt || !($stmt->bind_param('sissi', $mail, $active, $code_secret, $mdp, $admin)) || !($stmt->execute()))
                return false;
            else
                return true;
        }
        else
            return false;
    }

    public static function testerMdp($mysqli, $user, $mdp)
    {
        return ($user && (md5($mdp) == $user->mdp));
    }

}