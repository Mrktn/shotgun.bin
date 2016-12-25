<?php

class shotgun_event
{
    public $id;
    public $titre;
    public $description;
    public $date_event;
    public $date_publi;
    public $nb_places;
    public $prix;
    public $mail_crea;
    public $au_nom_de;
    public $anonymous;
    public $link_thumbnail;
    public $ouvert;
    public $contacte;
    public $active;
    public $date_crea;

    public function shotgunIsInDB($mysqli, $id)
    {
        // They see me checkin', they hatin'
        if(!ctype_digit($id))
            return false;

        $query = "SELECT ev.id FROM shotgun_event AS ev WHERE ev.id = $id LIMIT 1;";
        $result = $mysqli->query($query);
        
        if(!$result)
            die($mysqli->error);

        return ($result->num_rows != 0);
    }
    
    public function shotgunIsVisible($mysqli, $id)
    {
        if(!ctype_digit($id))
            return false;

        $query = "SELECT ev.id FROM shotgun_event AS ev WHERE ev.id = $id AND ev.ouvert=1 AND ev.active=1 AND NOW() > ev.date_publi LIMIT 1;";
        $result = $mysqli->query($query);
        
        if(!$result)
            die($mysqli->error);
        
        return ($result->num_rows != 0);
    }
    
    public function shotgunGet($mysqli, $id)
    {
        if(!ctype_digit($id))
            return null;
        
        $query = "SELECT * FROM shotgun_event AS ev WHERE ev.id = $id LIMIT 1;";
        $result = $mysqli->query($query);
        
        // If you can't do it quick, at least do it dirty
        while(($row = $result->fetch_object('shotgun_event')))
            return $row;
    }

    // Est visible quiconque est ouvert, actif, et pas encore périmé et dont la date d'apparition est dépassée
    public static function getVisibleShotguns($mysqli)
    {
        $a = array();
        $query = "SELECT * FROM shotgun_event AS ev WHERE NOW() < ev.date_event AND NOW() > ev.date_publi AND ev.active=1 AND ev.ouvert=1 ORDER BY ev.date_crea DESC;";

        $result = $mysqli->query($query);
        
        if(!$result)
            die($mysqli->error);

        while(($row = $result->fetch_object('shotgun_event')))
        {
            $a[] = $row;
        }
        
        return $a;
    }
    
    public static function getInactiveShotguns($mysqli)
    {
        $a = array();
        
        // On sélectionne ceux qui ne sont pas encore périmés, qui sont inactifs
        $query = "SELECT * FROM shotgun_event AS ev WHERE NOW() < ev.date_event AND ev.active=0 ORDER BY ev.date_crea ASC;";

        $result = $mysqli->query($query);
        
        if(!$result)
            die($mysqli->error);

        while(($row = $result->fetch_object('shotgun_event')))
        {
            $a[] = $row;
        }
        
        return $a;
    }
    
    // Retourne le nombre de paxs associés au shotgun d'id $id dans la db
    public static function getNumInscriptions($mysqli, $id)
    {
        $query = "SELECT * FROM inscription WHERE id_shotgun=".$id.";";
        
        $result = $mysqli->query($query);
        
        if($result)
        {
            return $result->num_rows;
        }
        
        else
        {
            die($mysqli->error);
        }
    }
    
    public static function getActiveAVenirShotguns($mysqli)
    {
        $a = array();
        
        // On sélectionne ceux qui ne sont pas ecore publiés à cause de leur date de publi, mais qui sont actifs
        $query = "SELECT * FROM shotgun_event AS ev WHERE NOW() < ev.date_publi AND ev.active=1 ORDER BY ev.date_crea ASC;";

        $result = $mysqli->query($query);
        
        if(!$result)
            die($mysqli->error);

        while(($row = $result->fetch_object('shotgun_event')))
        {
            $a[] = $row;
        }
        
        return $a;
    }

}