<?php

// Propriété: include ne crée pas d'erreur si la ressource n'existe pas.
// Si c'est jQuery qui contacte l'API pour refresh une progressbar, il faut remonter l'arborescence car on est dans api/
// Sinon, si c'est un content_X.php, on est déjà à la racine.

$filesDepuisContent = array('classes/DBi.php', 'classes/shotgun_event.php', 'utils.php');
$filesDepuisjQuery = array('../classes/DBi.php', '../classes/shotgun_event.php', '../utils.php');


if(file_exists($filesDepuisContent[0]))
{
    foreach($filesDepuisContent as $f)
        include_once($f);
}
else
{
    foreach($filesDepuisjQuery as $f)
        include_once($f);
}

// Retourner une jolie progressbar pour le shotgun donné

DBi::connect();
// shotgunIsInDB va vérifier qu'ona bien un entier dans le $_GET !
if(!isset($_GET['idShotgun']) || !shotgun_event::shotgunIsInDB(DBi::$mysqli, $_GET['idShotgun']))
    echo 'invalid shotgun provided : ' . $_GET['idShotgun'];
else
{
    $iid = $_GET['idShotgun'];
    $shotgun = shotgun_event::shotgunGet(DBi::$mysqli, strval($iid));

    // Maintenant on lance une petite requête pour savoir combien il y a d'inscriptions à ce shotgun pour l'instant.
    $k = shotgun_event::getNumInscriptions(DBi::$mysqli, $iid);
    $n = $shotgun->nb_places;

    if($n != 0)
    {
        $perc = $k / (float) $n;

        echo '
      <div class="progress-bar active ' . labelFromPercentage($perc) . '" role="progressbar" aria-valuenow="' . floor(100 * $perc) . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . floor(100 * $perc) . '%">
        <span><strong>' . $k . ' / ' . $n . '</strong></span>
    </div>';
    }
    else
    {
        echo '
      <div class="progress-bar progress-bar-info active" role="progressbar" aria-valuenow="' . 100 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . 100 . '%">
        <span><strong>' . $k . ' inscrits</strong></span>
    </div>';
    }
}