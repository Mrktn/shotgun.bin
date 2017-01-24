<?php

require_once('DBi.php');
require_once('shotgun_event.php');
require_once('utils.php');
// Retourner une jolie progressbar pour le shotgun donné
DBi::connect();
// shotgunIsInDB va vérifier qu'ona bien un entier dans le $_GET !
if(!isset($_GET['idShotgun']) || !shotgun_event::shotgunIsInDB(DBi::$mysqli, $_GET['idShotgun']))
    echo 'invalid shotgun provided : ' . $_GET['idShotgun'];
else
{
    $iid = $_GET['idShotgun'];
    $shotgun = shotgun_event::shotgunGet(DBi::$mysqli, strval($iid));

    // Maintenant on claque une petite requête pour savoir combien il y a d'inscriptions à ce shotgun pour l'instant.
    $k = shotgun_event::getNumInscriptions(DBi::$mysqli, $iid);
    $n = $shotgun->nb_places;

    $perc = $k / (float) $n;

    echo '
      <div class="progress-bar active ' . labelFromPercentage($perc) . '" role="progressbar" aria-valuenow="' . floor(100 * $perc) . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . floor(100 * $perc) . '%">
        <span><strong>' . $k . ' / ' . $n . '</strong></span>
    </div>';
}