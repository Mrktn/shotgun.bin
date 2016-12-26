<?php

class inscription
{
    public $id;
    public $id_shotgun;
    public $mail_user;
    public $date_shotgunned;
    
    // $idShot est l'id d'un shotgun. Quels sont les paxs inscrits à ce shotgun (par ordre d'inscription)
    public static function getInscriptionsIn($mysqli, $idShot)
    {
        $a = array();
        $query = "SELECT * FROM inscription AS ins WHERE ins.id_shotgun = $idShot ORDER BY ins.date_shotgunned ASC;";
        
        $result = $mysqli->query($query);
        
        if(!$result)
            die($mysqli->error);

        while(($row = $result->fetch_object('inscription')))
        {
            $a[] = $row;
        }
        
        return $a;
    }
    
    // Est-ce que $mail est déjà enregistré au shotgun $idShot ?
    public static function userIsRegistered($mysqli, $idShot, $mail)
    {
        if(!isValidPolytechniqueEmail($mail))
            header('Location: index.php?activePage=error&msg=Erreur fatale');

        $query = "SELECT * FROM inscription AS ins WHERE ins.id_shotgun='$idShot' AND ins.mail_user='$mail';";
        
        $result = $mysqli->query($query);
        
        if(!$result)
            die($mysqli->error);
        
        return ($result->num_rows != 0);
    }

}