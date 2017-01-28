<?php

// On récupère les shotguns dont je suis le créateur

if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])
    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "L'administrateur ne peut pas poster de shotguns !"));
        
if(!isset($_SESSION['mailUser']))
    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Erreur !"));

echo "<div class ='container-fluid titlepage' > <h1>Shotguns postés</h1> </div>";
echo '<div class="container center-block" style="width:100%; background-color: #ffffff">';
echo '<div class="container center-block" style="padding:15px">';

displayShotgunList(DBi::$mysqli, shotgun_event::getMyShotguns(DBi::$mysqli, $_SESSION['mailUser']), $_SESSION['mailUser']);

?>
