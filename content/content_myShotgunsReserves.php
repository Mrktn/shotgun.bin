<?php

//Récupèration des shotguns que j'ai sécurisé et affichage

if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])
    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "L'administrateur ne peut pas s'inscrire !"));
     
if(!isset($_SESSION['loggedIn']) || !isset($_SESSION['mailUser']))
    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Accès interdit !"));

echo "<div class ='container-fluid titlepage' > <h1>Mes shotguns</h1> </div><br/><br/>";
echo '<div class="container center-block" style="width:100%; background-color: #ffffff">';
echo '<div class="container center-block" style="padding:15px">';

$shotguns = shotgun_event::getMyShotgunsReservesNonPerimes(DBi::$mysqli, $_SESSION['mailUser']);

if(count($shotguns) != 0)
    displayShotgunList(DBi::$mysqli, $shotguns, $_SESSION['mailUser']);
else
    echo "<h2>Il n'y a rien à afficher !</h2>";

echo '</div></div>';
?>
