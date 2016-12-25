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

}