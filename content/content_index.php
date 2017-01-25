<?php
if (!isset($_SESSION['mailUser']))
{ // Page d'accueil pour les non inscrits
    echo'
   <div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-12 control-label fondColor">
            <p>Bloc Shotgun à venir</p>
';
displayShotgunAVenir(DBi::$mysqli, shotgun_event::getActiveAVenirShotguns(DBi::$mysqli)); 
} else
{ // Page d'accueil pour les inscrits


echo'
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-5 col-md-5 control-label fondColor">
            <p>Bloc Shotgun à venir</p>
';
displayShotgunAVenir(DBi::$mysqli, shotgun_event::getActiveAVenirShotguns(DBi::$mysqli));
echo'
       </div>
        <div class="col-sm-2 col-md-2 control-label questionStyle ">
            <p>Bloc de transition</p>
            
        </div>
        <div class="col-sm-5 col-md-5 control-label divform">
            <p>Bloc Mon agenda</p>';

            displayMonAgenda(DBi::$mysqli, shotgun_event::getMyShotgunsReserves(DBi::$mysqli, $_SESSION['mailUser'])); 
 echo'       </div>;
</div> </div>';
};