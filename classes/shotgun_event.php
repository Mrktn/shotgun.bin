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

    public static function insererShotgun($mysqli, $titre, $description, $date_event, $date_publi, $nb_places, $prix, $mail_crea, $au_nom_de, $anonymous, $link_thumbnail, $ouvert, $active)
    {
        $date_crea = date("Y-m-d H:i:s");
        $query = "INSERT INTO `shotgun_event` (`titre`, `description`, `date_event`, `date_publi`, `nb_places`, `prix`, `mail_crea`, `au_nom_de`,`anonymous`,`link_thumbnail`,`ouvert`,`active`,`date_crea`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);

        if(!$stmt)
            die($stmt->error);

        $stmt->bind_param('ssssidssisiis', $titre, $description, $date_event, $date_publi, $nb_places, $prix, $mail_crea, $au_nom_de, $anonymous, $link_thumbnail, $ouvert, $active, $date_crea);
        if(!$stmt->execute())
        {
            die($stmt->error);
        }
        $idShotgun = $stmt->insert_id;
        return $idShotgun;
    }

    public static function getShotgun($mysqli, $id)
    { // renvoie le shotgun d'id id et faux sinon , fait-on la même avec en paramètre titre?
        $query = "SELECT * FROM `shotgun_event` WHERE id = ?;";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $id);
        if(!$stmt->execute())
        {
            die($stmt->error);
        }
        $result = $stmt->get_result();
        $shotgun = $result->fetch_object('shotgun_event'); // Renvoie false si le shotgun n'existe pas
        $stmt->close();
        return $shotgun;
    }

    public static function updateShotgun($mysqli, $idShotgun, $action)
    {
        $query = "";

        if(!shotgun_event::shotgunIsInDB($mysqli, $idShotgun))
            return false;

        if($action == 'deleteShotgun')
            $query = "DELETE FROM shotgun_event WHERE id=$idShotgun;";
        else if($action == 'closeShotgun')
            $query = "UPDATE shotgun_event SET ouvert=0 WHERE id='$idShotgun';";
        else if($action == 'openShotgun')
            $query = "UPDATE shotgun_event SET ouvert=1 WHERE id=$idShotgun;";
        else if($action == 'activateShotgun')
            $query = "UPDATE shotgun_event SET active=1 WHERE id=$idShotgun;";
        else if($action == 'disableShotgun')
            $query = "UPDATE shotgun_event SET active=0 WHERE id=$idShotgun;";

        $result = $mysqli->query($query);

        if(!$result)
            die($mysqli->error);
        
        return true;
    }

    // Ça devrait s'appeler shotgunIsInDBEtidEstBienFormé.
    public static function shotgunIsInDB($mysqli, $id)
    {
        $iid = 0;
        $query = "SELECT * FROM shotgun_event AS ev WHERE ev.id = ? LIMIT 1";

        // Selon l'appeleur, c'est une string ou un entier. Les deux sont valides sémantiquement !
        if(is_string($id))
        {
            if(!ctype_digit($id))
                return false;
            else
                $iid = intval($id);
        }

        else if(is_int($id))
            $iid = $id;
        else
            return false;

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $iid);
        
        if(!$stmt->execute())
            die($stmt->error);

        $result = $stmt->get_result();

        if(!$result)
            die($mysqli->error);

        return ($result->num_rows != 0);
    }

    public static function shotgunIsVisible($mysqli, $id)
    {
        if(!shotgun_event::shotgunIsInDB($mysqli, $id))
            return false;

        $query = "SELECT ev.id FROM shotgun_event AS ev WHERE ev.id = $id AND ev.ouvert=1 AND ev.active=1 AND NOW() > ev.date_publi LIMIT 1;";
        $result = $mysqli->query($query);

        if(!$result)
            die($mysqli->error);

        return ($result->num_rows != 0);
    }

    public static function shotgunIsPerime($mysqli, $id)
    {
        if(!shotgun_event::shotgunIsInDB($mysqli, $id))
            return false;

        $query = "SELECT ev.id FROM shotgun_event AS ev WHERE ev.id = $id AND NOW() >= ev.date_event LIMIT 1;";
        $result = $mysqli->query($query);

        if(!$result)
            die($mysqli->error);

        return ($result->num_rows != 0);
    }

    public static function shotgunGet($mysqli, $id)
    {
        if(!shotgun_event::shotgunIsInDB($mysqli, $id))
            return null;

        $query = "SELECT * FROM shotgun_event AS ev WHERE ev.id = $id LIMIT 1;";
        $result = $mysqli->query($query);

        // If you can't do it quick, at least do it dirty
        while(($row = $result->fetch_object('shotgun_event')))
            return $row;
        
        return null;
    }

    // Est visible quiconque est ouvert, actif, et pas encore périmé et dont la date d'apparition est dépassée
    public static function getVisibleShotgunsNotMine($mysqli, $mailUser)
    {
        $a = array();

        // FIXME: optionnel ?
        if(!isValidPolytechniqueEmail($mailUser))
            header('Location: index.php?activePage=error&msg=Votre adresse est mal formée :o !');

        $query = "SELECT * FROM shotgun_event AS ev WHERE NOW() < ev.date_event AND NOW() > ev.date_publi AND ev.active=1 AND ev.ouvert=1 AND ev.mail_crea!=\"$mailUser\" ORDER BY ev.date_crea DESC;";

        $result = $mysqli->query($query);

        if(!$result)
            die($mysqli->error);

        while(($row = $result->fetch_object('shotgun_event')))
            $a[] = $row;

        return $a;
    }

    public static function getMyShotguns($mysqli, $mailCrea) // Renvoie la liste des shotguns que l'utilisateur a crée
    {
        $a = array();

        if(!isValidPolytechniqueEmail($mailCrea))
            header('Location: index.php?activePage=error&msg=Votre adresse est mal formée :o !');

        // On sélectionne ceux qui ne sont pas encore périmés, qui sont inactifs
        $query = "SELECT * FROM shotgun_event AS ev WHERE ev.mail_crea=\"$mailCrea\" ORDER BY ev.date_crea DESC;";

        $result = $mysqli->query($query);

        if(!$result)
            die($mysqli->error);

        while(($row = $result->fetch_object('shotgun_event')))
            $a[] = $row;

        return $a;
    }

    // Renvoie la liste des shotguns auxquels l'utilisateur s'est inscrit
    public static function getMyShotgunsReserves($mysqli, $mailCrea)
    {
        $a = array();

        if(!isValidPolytechniqueEmail($mailCrea))
            header('Location: index.php?activePage=error&msg=Votre adresse est mal formée :o !');

        // On sélectionne ceux qui ne sont pas encore périmés, qui sont inactifs
        $query = "SELECT shotgun_event.* FROM shotgun_event,inscription WHERE inscription.mail_user = ? AND inscription.id_shotgun = shotgun_event.id ORDER BY shotgun_event.date_crea DESC;";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('s', $mailCrea);

        if(!$stmt->execute())
            die($stmt->error);

        $result = $stmt->get_result();

        if(!$result)
            die($mysqli->error);

        while(($row = $result->fetch_object('shotgun_event')))
            $a[] = $row;

        return $a;
    }

    // Retourne le nombre de paxs associés au shotgun d'id $id dans la db
    public static function getNumInscriptions($mysqli, $id)
    {
        $query = "SELECT * FROM inscription WHERE id_shotgun=" . $id . ";";

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

    public static function getActiveAVenirShotguns($mysqli)
    {
        $a = array();

        // On sélectionne ceux qui ne sont pas ecore publiés à cause de leur date de publi, mais qui sont actifs
        $query = "SELECT * FROM shotgun_event AS ev WHERE NOW() < ev.date_publi AND ev.active=1 ORDER BY ev.date_crea ASC;";

        $result = $mysqli->query($query);

        if(!$result)
            die($mysqli->error);

        while(($row = $result->fetch_object('shotgun_event')))
            $a[] = $row;

        return $a;
    }

    public static function userMayCloseOpenShotgun($mysqli, $idShotgun, $mailUser, $isAdmin)
    {
        $shotgun = shotgun_event::shotgunGet($mysqli, $idShotgun);

        if($shotgun == null)
            return false;
        else
            return $isAdmin || ($shotgun->mail_crea == $mailUser);
    }
    
    // En fait c'est exactement la même condition que pour ouvrir / fermer, il faut être admin...
    // ...ou créateur !
    public static function userMayDeleteShotgun($mysqli, $idShotgun, $mailUser, $isAdmin)
    {
        return shotgun_event::userMayCloseOpenShotgun($mysqli, $idShotgun, $mailUser, $isAdmin);
    }
    
    public static function userMayActivateDisableShotgun($mysqli, $idShotgun, $mailUser, $isAdmin)
    {
        return $isAdmin && shotgun_event::shotgunIsInDB($mysqli, $idShotgun);
    }

    public static function userMayViewShotgunRecord($mysqli, $mailUser, $idShotgun, $isAdmin)
    {
        $shotgun = shotgun_event::shotgunGet($mysqli, $idShotgun);

        if($shotgun == null)
            return false;

        if($isAdmin)
            return true;

        if($mailUser == $shotgun->mail_crea)
            return true;

        return (shotgun_event::shotgunIsVisible($mysqli, $idShotgun) && !shotgun_event::shotgunIsPerime($mysqli, $idShotgun));
    }

    public static function getShotgunFromQuestion($mysqli, $id)
    {
        $query = "SELECT shotgun_event.* FROM question,shotgun_event WHERE question.id =? AND shotgun_event.id = id_shotgun ";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $id);
        if(!$stmt->execute())
        {
            die($stmt->error);
        }
        $result = $stmt->get_result();
        $row = $result->fetch_object('shotgun_event');
        $stmt->close();
        return $row;
    }

    public static function traiteShotgunForm($mysqli, $titre, $description, $date_event, $date_publi, $nb_places, $prix, $mail_crea, $au_nom_de, $anonymous, $link_thumbnail)
    {
        return shotgun_event::insererShotgun($mysqli, $titre, $description, $date_event, $date_publi, $nb_places, $prix, $mail_crea, $au_nom_de, $anonymous, $link_thumbnail, 0, 0);
    }

}