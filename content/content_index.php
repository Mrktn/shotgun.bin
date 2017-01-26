<?php
echo "<div class ='container-fluid' style='background-color:#F5F5F5;border-style:solid;border-width:1px;'> <h1 style = 'margin-top:20px; text-align:center'>Bienvenue sur le site de shotgun de l'X!</h1><hr />
 </div>
                    <div>       <img class='centrimage' style='display:block;margin-left:auto;margin-right:auto;margin-bottom:10px;margin-top:10px;' src='images/fusilsretoucheresize.jpg' alt ='Crossed Guns' />  </div>";
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
        <div class=" col-sm-2 col-md-2 control-label styleblocmid" >
        </div>
        <div class=" col-sm-5 col-md-5 control-label fontgrisContour">
';

            displayMonAgenda(DBi::$mysqli, shotgun_event::getMyShotgunsReserves(DBi::$mysqli, $_SESSION['mailUser'])); 
 echo'       </div>
</div> </div>';
};

echo"       <blockquote  class='blockquote-reverse'>
                        <i>L'avenir appartient à ceux qui shotgun tôt...</i>
                        <footer>Balestrat-san dans <cite title='Kravmagazine'>Kravmagazine</cite></footer>
                    </blockquote>";
echo'            <div id="footer">
	<p> Réalisé par Antoine Balestrat et Marc Revol, élèves de la promotion X2015 </p> 	
        </div>';
