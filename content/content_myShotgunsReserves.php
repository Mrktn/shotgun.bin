<?php

// On récupère les shotguns dont je suis le créateur

if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])
    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "L'administrateur ne peut pas s'inscrire !"));
     
if(!isset($_SESSION['loggedIn']) || !isset($_SESSION['mailUser']))
    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Accès interdit !"));
     

echo "<div class ='container-fluid titlepage' > <h1>Mes shotguns</h1> </div><br/><br/>";
echo '<div class="container center-block" style="width:100%; background-color: #ffffff">';
echo '<div class="container center-block" style="padding:15px">';

displayShotgunList(DBi::$mysqli, shotgun_event::getMyShotgunsReserves(DBi::$mysqli, $_SESSION['mailUser']), $_SESSION['mailUser']);

//echo '<script src="js/refresher.js"></script>';
?>
