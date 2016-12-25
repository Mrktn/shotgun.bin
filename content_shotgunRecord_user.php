<?php

if(!isset($_GET['idShotgun']) || !shotgun_event::shotgunIsInDB($mysqli, $_GET['idShotgun']))
    header('Location: index.php?activePage=error&msg=Impossible d\'afficher ce shotgun !');

// À ce stade on sait que le shotgun est dans la database.

$id = $_GET['idShotgun'];



if(!shotgun_event::shotgunIsVisible($mysqli, $id))
    header('Location: index.php?activePage=error&msg=Vous n\'avez pas les permissions pour voir ce shotgun !');

// Mais pourquoi ne pas l'avoir récupéré avant le précédent if pour s'épargner une requête ?
// Parce que je suis pas un grand fan de la comparaison de dates dans PHP, je préfère laisser ça à SQL où ça se fait avec un <
$shotgun = shotgun_event::shotgunGet($mysqli, $id);
$k = shotgun_event::getNumInscriptions($mysqli, $id);
$n = $shotgun->nb_places;
// À ce stade on sait que l'utilisateur peut consulter le shotgun.

echo '
<div class="container">
<div>
    <header class="page-header">
    <h1 class="page-title">' . utf8_encode($shotgun->titre) . '</h1>
    <small> <i class="fa fa-clock-o"></i> Posté le <time>' . strftime("%d %B %Y à %H:%M", strtotime($shotgun->date_crea)) . '</time> par ' . stripTheMail($shotgun->mail_crea) . '</small>
  </header>
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-offset-10 col-md-5 col-lg-offset-0 col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading resume-heading">
        <div class="row">
          <div class="col-lg-12">
            <div class="col-xs-12 col-sm-4">
              <figure>
                <img height="200" width="200" class="img-circle img-responsive img-thumbnail" alt="alt text" src=" ' . $shotgun->link_thumbnail . '" onerror="this.src=\'http://staging-us.armscor.com/assets/users/products/612/ria_m5_shotgun_mattnickel_12ga_edited-1.jpg\'">
              </figure>
            </div>

            <div class="col-xs-12 col-sm-8">
              <ul class="list-group">
                <li class="list-group-item"><strong>Auteur:</strong> ' . utf8_encode($shotgun->au_nom_de) . '</li>
                <li class="list-group-item"><strong>Date:</strong> le ' . strftime("%d %B %Y à %H:%M", strtotime($shotgun->date_crea)) . '</li>
                <li class="list-group-item">' . '<div class="row" class="col-sm-6"> 
    <span class="col-sm-3"><strong >Effectifs:</strong> </span>' . generateProgressBar($k, $n) . '</li>
                <li class="list-group-item"><strong>Prix: </strong>' . $shotgun->prix . '€ </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="bs-callout bs-callout-danger">
        <h4><strong>Description</strong></h4>
        <p>
         ' . utf8_encode(nl2br($shotgun->description)) .
 '</p>
      </div>
      
  </div>
</div>
    
</div>

</div>
';
?>