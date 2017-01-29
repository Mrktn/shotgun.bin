<?php
// Sert à implémenter la classe shotgun_event reliée à la base de donnée ainsi que toutes les fonctions utilisant la classe
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

        if(!$stmt || !($stmt->bind_param('ssssidssisiis', $titre, $description, $date_event, $date_publi, $nb_places, $prix, $mail_crea, $au_nom_de, $anonymous, $link_thumbnail, $ouvert, $active, $date_crea)) || !($stmt->execute()))
            return null;

        $idShotgun = $stmt->insert_id;

        $stmt->close();
        return $idShotgun;
    }

    public static function updateShotgun($mysqli, $idShotgun, $action)
    {
        $query = "";

        if(!shotgun_event::shotgunIsInDB($mysqli, $idShotgun))
            return false;

        if($action == 'deleteShotgun')
            $query = "DELETE FROM shotgun_event WHERE id = ?;";
        else if($action == 'closeShotgun')
            $query = "UPDATE shotgun_event SET ouvert=0 WHERE id = ?;";
        else if($action == 'openShotgun')
            $query = "UPDATE shotgun_event SET ouvert=1 WHERE id = ?;";
        else if($action == 'activateShotgun')
            $query = "UPDATE shotgun_event SET active=1 WHERE id = ?;";
        else if($action == 'disableShotgun')
            $query = "UPDATE shotgun_event SET active=0 WHERE id = ?;";

        $stmt = $mysqli->prepare($query);

        if(!$stmt || !($stmt->bind_param('i', $idShotgun)) || !($stmt->execute()))
            return false;

        $stmt->close();
        return true;
    }

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

        if(!$stmt || !($stmt->bind_param('i', $iid)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die('Erreur fatale dans shotgunIsInDB');

        $stmt->close();
        return ($result->num_rows != 0);
    }

    public static function shotgunIsVisible($mysqli, $id)
    {
        if(!shotgun_event::shotgunIsInDB($mysqli, $id))
            return false;

        $query = "SELECT ev.id FROM shotgun_event AS ev WHERE ev.id = ? AND ev.ouvert=1 AND ev.active=1 AND NOW() > ev.date_publi LIMIT 1;";
        $stmt = $mysqli->prepare($query);

        if(!$stmt || !($stmt->bind_param('i', $id)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die('Erreur fatale dans shotgunIsVisible');

        $stmt->close();
        return ($result->num_rows != 0);
    }

    public static function shotgunIsPerime($mysqli, $id)
    {
        if(!shotgun_event::shotgunIsInDB($mysqli, $id))
            return false;

        $query = "SELECT ev.id FROM shotgun_event AS ev WHERE ev.id = ? AND NOW() >= ev.date_event LIMIT 1;";
        $stmt = $mysqli->prepare($query);

        if(!$stmt || !($stmt->bind_param('i', $id)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die('Erreur fatale dans shotgunIsPerime');

        $stmt->close();
        return ($result->num_rows != 0);
    }

    public static function shotgunGet($mysqli, $id)
    {
        if(!shotgun_event::shotgunIsInDB($mysqli, $id))
            return null;

        $query = "SELECT * FROM shotgun_event AS ev WHERE ev.id = ? LIMIT 1;";
        $stmt = $mysqli->prepare($query);

        if(!$stmt || !($stmt->bind_param('i', $id)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die('Erreur fatale dans shotgunGet');

        $row = $result->fetch_object('shotgun_event');
        $stmt->close();
        return $row;
    }

    // Est visible un shotgun ouvert, actif, et pas encore périmé et dont la date d'apparition est dépassée
    public static function getVisibleShotgunsNotMine($mysqli, $mailUser)
    {
        $a = array();

        $query = "SELECT * FROM shotgun_event AS ev WHERE NOW() < ev.date_event AND NOW() > ev.date_publi AND ev.active=1 AND ev.ouvert=1 AND ev.mail_crea != ? ORDER BY ev.date_crea DESC;";
        $stmt = $mysqli->prepare($query);

        if(!$stmt || !($stmt->bind_param('s', $mailUser)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die('Erreur fatale dans getVisibleShotgunsNotMine');

        while(($row = $result->fetch_object('shotgun_event')))
            $a[] = $row;

        $stmt->close();
        return $a;
    }

    public static function getMyShotguns($mysqli, $mailCrea) // Renvoie la liste des shotguns que l'utilisateur a créé
    {
        $a = array();

        // On sélectionne ceux qui ne sont pas encore périmés, qui sont inactifs
        $query = "SELECT * FROM shotgun_event AS ev WHERE ev.mail_crea = ? ORDER BY ev.date_crea DESC;";
        $stmt = $mysqli->prepare($query);

        if(!$stmt || !($stmt->bind_param('s', $mailCrea)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die('Erreur fatale dans getMyShotguns');

        while(($row = $result->fetch_object('shotgun_event')))
            $a[] = $row;

        $stmt->close();
        return $a;
    }

    // Renvoie la liste des shotguns auxquels l'utilisateur s'est inscrit
    public static function getMyShotgunsReservesNonPerimes($mysqli, $mailCrea)
    {
        $a = array();

        // On sélectionne ceux qui ne sont pas encore périmés, qui sont inactifs
        $query = "SELECT shotgun_event.* FROM shotgun_event,inscription WHERE inscription.mail_user = ? AND inscription.id_shotgun = shotgun_event.id AND NOW() <= date_event ORDER BY shotgun_event.date_event ASC;";

        $stmt = $mysqli->prepare($query);

        if(!$stmt || !($stmt->bind_param('s', $mailCrea)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die('Erreur fatale dans getMyShotgunsReserves');

        while(($row = $result->fetch_object('shotgun_event')))
            $a[] = $row;

        $stmt->close();
        return $a;
    }

    // Retourne le nombre d'utilisateurs inscrits au shotgun d'id $id dans la db
    public static function getNumInscriptions($mysqli, $id)
    {
        $query = "SELECT * FROM inscription WHERE id_shotgun = ?;";

        $stmt = $mysqli->prepare($query);
        
        if(!$stmt || !($stmt->bind_param('i', $id)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die('Erreur fatale dans getNumInscriptions');

        $stmt->close();
        return $result->num_rows;
    }

    public static function getInactiveShotguns($mysqli)
    {
        $a = array();

        // On sélectionne ceux qui ne sont pas encore périmés, qui sont inactifs
        $query = "SELECT * FROM shotgun_event AS ev WHERE NOW() < ev.date_event AND ev.active=0 ORDER BY ev.date_crea ASC;";
        $stmt = $mysqli->prepare($query);

        if(!$stmt || !($stmt->execute()) || !($result = $stmt->get_result()))
            die('Erreur fatale dans getInactiveShotguns');

        while(($row = $result->fetch_object('shotgun_event')))
            $a[] = $row;

        $stmt->close();
        return $a;
    }

    // Est visible un shotgun ouvert, actif, et pas encore périmé et dont la date d'apparition est dépassée
    public static function getVisibleShotguns($mysqli)
    {
        $a = array();

        $query = "SELECT * FROM shotgun_event AS ev WHERE NOW() < ev.date_event AND NOW() > ev.date_publi AND ev.active=1 AND ev.ouvert=1 ORDER BY ev.date_crea DESC;";
        $stmt = $mysqli->prepare($query);

        if(!$stmt || !($stmt->execute()) || !($result = $stmt->get_result()))
            die('Erreur fatale dans getVisibleShotguns');

        while(($row = $result->fetch_object('shotgun_event')))
            $a[] = $row;

        $stmt->close();
        return $a;
    }

    public static function getActiveAVenirShotguns($mysqli)
    {
        $a = array();

        // On sélectionne ceux qui ne sont pas ecore publiés à cause de leur date de publi, mais qui sont actifs
        $query = "SELECT * FROM shotgun_event AS ev WHERE NOW() < ev.date_publi AND ev.active=1 ORDER BY ev.date_publi ASC;";

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

    public static function traiteShotgunForm($mysqli, $titre, $description, $date_event, $date_publi, $nb_places, $prix, $mail_crea, $au_nom_de, $anonymous, $link_thumbnail)
    {
        return shotgun_event::insererShotgun($mysqli, $titre, $description, $date_event, $date_publi, $nb_places, $prix, $mail_crea, $au_nom_de, $anonymous, $link_thumbnail, 1, 0);
    }
}
