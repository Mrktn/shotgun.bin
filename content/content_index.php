
<div class ='container-fluid titlepage' >
    <div class="col-sm-4 col-md-4 ">
        <img alt="Canons croisés" src="resources/fusilsretoucheresize_transparent.png" class="img-responsive Photoleft">
    </div>
    <h1 class="col-sm-4 col-md-4" >Bienvenue sur le site de shotgun de l'X !</h1>
    <div class="col-sm-4 col-md-4 ">
        <img alt="Canons croisés" src="resources/fusilsretoucheresize_transparent.png" class="Photoright img-responsive">
    </div>
</div>
<?php
//Page d'accueil pour les non inscrits
if(!isset($_SESSION['mailUser']) || $_SESSION['isAdmin'])
{
    echo'
   <div class="container">
    <div class="row" >
        <div class="col-sm-12 col-md-12 control-label fongrisContour">
';
    displayShotgunAVenir(DBi::$mysqli, shotgun_event::getActiveAVenirShotguns(DBi::$mysqli));
    echo '</div></div>';
}
else
{ // Page d'accueil pour les inscrits
    echo'
<div class="container">
    <div class="row row-eq-height" >
        <div class=" col-sm-5 col-md-5 control-label fontgrisContour">

';
    displayShotgunAVenir(DBi::$mysqli, shotgun_event::getActiveAVenirShotguns(DBi::$mysqli));
    echo'
       </div>
        <div class=" col-sm-2 col-md-2 control-label styleblocmid" >
        </div>
        <div class=" col-sm-5 col-md-5 control-label fontgrisContour">
';

    displayMonAgenda(DBi::$mysqli, $_SESSION['mailUser']);
    echo'       </div>
</div> ';
};

echo" </div> </div><div class='container'> <blockquote  class='blockquote-reverse' style='margin-top:10px'>
                        <i>L'avenir appartient à ceux qui shotgun tôt...</i>
                        <footer>Louis Vaneau, X1829</footer> 
                    </blockquote></div>";
