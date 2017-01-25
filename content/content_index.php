<?php
echo '<h1>Bienvenue sur shotgun.bin</h1>'
. "                    <blockquote  class='blockquote-reverse'>
                        <i>L'avenir appartient à ceux qui shotgun tôt</i>
                        <footer>Balestrat-san dans <cite title='Kravmagazine'>Kravmagazine</cite></footer>
                    </blockquote>";
if (!isset($_SESSION['mailUser']))
{ // Page d'accueil pour les non inscrits
    echo'
   <div class="container-fluid">
    <div class="row" >
        <div class="col-sm-12 col-md-12 control-label fondColor">
';
displayShotgunAVenir(DBi::$mysqli, shotgun_event::getActiveAVenirShotguns(DBi::$mysqli)); 
} else
{ // Page d'accueil pour les inscrits

    
echo'
<div class="container-fluid">
    <div class="row row-eq-height" style="">
        <div class=" col-sm-5 col-md-5 control-label fontgrisContour">

';
displayShotgunAVenir(DBi::$mysqli, shotgun_event::getActiveAVenirShotguns(DBi::$mysqli));
echo'
       </div>
        <div class=" col-sm-2 col-md-2 control-label questionStyle ">
            <p>Bloc de transition</p>
            
        </div>
        <div class=" col-sm-5 col-md-5 control-label fontgrisContour">
';

            displayMonAgenda(DBi::$mysqli, shotgun_event::getMyShotgunsReserves(DBi::$mysqli, $_SESSION['mailUser'])); 
 echo'       </div>
</div> </div>';
};
echo'            <div id="footer">
	<p> Réalisé par Antoine Balestrat et Marc Revol, élèves de la promotion X2015 </p> 	
        </div>';
