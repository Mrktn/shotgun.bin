<?php

if(!isset($_SESSION['loggedIn']) || !isset($_SESSION['mailUser']))
    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => 'Accès interdit !'));

echo "<div class ='container-fluid titlepage' > <h1>Shotguns ouverts</h1> </div><br/><br/>";
echo '<div class="container center-block" style="width:100%; background-color: #ffffff">';
echo '<div class="container center-block" style="padding:15px">';

// On récupère les shotguns qui sont visibles, aka ouverts et actifs et non périmés
displayShotgunList(DBi::$mysqli, shotgun_event::getVisibleShotguns(DBi::$mysqli, $_SESSION['mailUser']), $_SESSION['mailUser']);

echo '</div></div>';
?>
