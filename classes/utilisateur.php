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
        $stmt = $mysqli->prepare("SELECT * FROM `utilisateur` WHERE mail = ? LIMIT 1");

        if(!$stmt || !($stmt->bind_param('s', $mail)) || !($stmt->execute()))
            die("Erreur irrécupérable dans getUtilisateur");

        $result = $stmt->get_result();
        $row = $result->fetch_object('utilisateur');
        return $row;
    }

    public static function insererUtilisateur($mysqli, $mail, $active, $code_secret, $mdp, $admin)
    {
        if(!utilisateur::getUtilisateur($mysqli, $mail))
        {
            $stmt = $mysqli->prepare("INSERT INTO `utilisateur` (`mail`, `active`, `code_secret`, `mdp`, `admin`) VALUES(?,?,?,MD5(?),?)");

            if(!$stmt || !($stmt->bind_param('sissi', $mail, $active, $code_secret, $mdp, $admin)) || !($stmt->execute()))
                die("Erreur irrécupérable dans insererUtilisateur");
            else
            {
                $stmt->close();
                return true;
            }
        }
        else
            return false;
    }

    public static function updatePassword($mysqli, $mail, $newpass)
    {
        $stmt = $mysqli->prepare("UPDATE `utilisateur` SET `mdp` = ? WHERE mail = ?");
        $md5ed = md5($newpass);

        if(!$stmt || !($stmt->bind_param('ss', $md5ed, $mail)) || !($stmt->execute()))
            die("Erreur irrécupérable dans updatePassword.");

        $stmt->close();
        return true;
    }

    public static function testerMdp($mysqli, $user, $mdp)
    {
        return ($user && (md5($mdp) == $user->mdp));
    }
}