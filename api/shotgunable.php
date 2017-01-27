<?php

/*
 * API qui répond 0 si le shotgun du paramètre est rempli à craquer et 1 sinon
 */

include_once('../classes/DBi.php');
include_once('../classes/shotgun_event.php');
include_once('../utils.php');

DBi::connect();

if(!isset($_GET['idShotgun']) || !shotgun_event::shotgunIsInDB(DBi::$mysqli, $_GET['idShotgun']))
    echo 'invalid shotgun provided : ' . $_GET['idShotgun'];
    //echo '0'; // contestable

else
{
    $iid = $_GET['idShotgun'];
    $shotgun = shotgun_event::shotgunGet(DBi::$mysqli, strval($iid));
    $n = $shotgun->nb_places;

    if($n == 0)
        echo '1';
    else
    {
        $k = shotgun_event::getNumInscriptions(DBi::$mysqli, $iid);
        echo $k >= $n ? '0' : '1';
    }
}
