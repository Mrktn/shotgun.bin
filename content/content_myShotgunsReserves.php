<?php

// On récupère les shotguns dont je suis le créateur

if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])
    header('Location: index.php?activePage=error&msg=L\'administrateur ne peut pas faire de shotguns !');
if(!isset($_SESSION['mailUser']))
    header('Location: index.php?activePage=error&msg=Erreur inconnue !');


echo '<div class="container center-block" style="width:100%; background-color: #ffffff">';
echo "<h1>Mes shotguns</h1>";
echo '<div class="container center-block" style="padding:15px">';

displayShotgunList(DBi::$mysqli, shotgun_event::getMyShotgunsReserves(DBi::$mysqli, $_SESSION['mailUser']));

echo '<script src="js/refresher.js"></script>';
?>