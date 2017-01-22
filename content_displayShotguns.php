<?php

// Provides you with an eye-candy overview of current shotguns ! Yay !
//$mysqli = Database::connect();


// On récupère les shotguns qui sont visibles, aka ouverts et actifs et non périmés
$shotguns = shotgun_event::getVisibleShotgunsNotMine($mysqli, $_SESSION['mailUser']);

echo '<div class="container">';
foreach($shotguns as $currShotgun)
{
    // Maintenant on claque une petite requête pour savoir combien il y a d'inscriptions à ce shotgun pour l'instant.
    $k = shotgun_event::getNumInscriptions($mysqli, $currShotgun->id);
    $n = $currShotgun->nb_places;
    
    // Calcul d'une combinaison linéaire de vert et de rouge pour afficher une jolie barre
    $r = $k / (float) $n;
    $red = floor($r*255.0);
    $green = floor(255.0*(1-$r));

    $perc = floor(100 * ($k / (float) $n));

    echo '<div idShotgun="'. $currShotgun->id .'" class="panel panel-default center-block shotgunPanel" style="align:center">
  <div class="panel-heading">
    <h3 class="panel-title">' . htmlspecialchars(utf8_encode($currShotgun->titre)) . ' par ' . htmlspecialchars(utf8_encode($currShotgun->au_nom_de)) .  ' </h3>
  </div>
  <div class="panel-body">' .
     generateProgressBar($k, $n) .
'</div>'.
    nl2br(htmlspecialchars(utf8_encode($currShotgun->description)));
    
    echo '</div></div>';
    
}

echo '</div>';
?>