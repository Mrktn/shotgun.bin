<?php

require_once('inscription.php');
if(!isset($_GET['idShotgun']) || !shotgun_event::shotgunIsInDB($mysqli, $_GET['idShotgun']))
    header('Location: index.php?activePage=error&msg=Impossible d\'afficher ce shotgun !');

// À ce stade on sait que le shotgun est dans la database.

$id = $_GET['idShotgun'];

/*
// Un admin peut tout voir a priori
if(!shotgun_event::shotgunIsVisible($mysqli, $id))
    header('Location: index.php?activePage=error&msg=Vous n\'avez pas les permissions pour voir ce shotgun !');*/

$shotgun = shotgun_event::shotgunGet($mysqli, $id);
$k = shotgun_event::getNumInscriptions($mysqli, $id);
$n = $shotgun->nb_places;
// À ce stade on sait que l'utilisateur peut consulter le shotgun.

echo '
<div class="container">
<div>
    <header class="page-header">
    <h1 class="page-title">' . utf8_encode($shotgun->titre) . '  '  . '</h1>
    <small> <i class="fa fa-clock-o"></i> Ajouté le <time>' . strftime("%d %B %Y à %H:%M", strtotime($shotgun->date_crea)) . '</time> par ' . stripTheMail($shotgun->mail_crea) . '</small>
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
 '</p><br/>
           
<h4><strong>Liste des participants</strong></h4>
        ';

if($shotgun->anonymous)
{
    echo '<p>L\'auteur du shotgun n\'a pas souhaité rendre la liste des participants visibles !</p>';
}
else
{
    $arrayInscriptions = inscription::getInscriptionsIn($mysqli, $shotgun->id);
    $tableInscriptions = array();

    echo '<div style="margin:50px"><table style="margin-bottom:10px" id="oklm" class="table-fill">
               <thead>
                <tr>
                  <th>Rang</th>
                  <th>Mail</th>
                </tr>
              </thead><tbody>';

    $i = 1;
    foreach($arrayInscriptions as $ins)
    {
        echo "<tr><td>$i</td><td>" . utf8_encode($ins->mail_user) . '</td></tr>';
        $i = $i + 1;
    }

    echo '</tbody></table><script type="text/javascript">$(\'#oklm\').dynatable();</script>';

// Si je suis un admin ou le créateur du shotgun j'ai le droit de télécharger le csv
    if(((isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) || (isset($_SESSION['mailUser']) && $_SESSION['mailUser'] == $shotgun->mail_crea)))
    {
        $i = 1;
        $j = 0;
        echo "<script type=\"text/javascript\">var data = [";
        for($j = 0; $j < (count($arrayInscriptions) - 1); $j = $j + 1)
        {
            echo "['$i','" . $arrayInscriptions[$j]->date_shotgunned . "','" . utf8_encode($arrayInscriptions[$j]->mail_user) . "'],";
            $i = $i + 1;
        }
        echo "['$i','" . $arrayInscriptions[$j]->date_shotgunned . "','" . utf8_encode($arrayInscriptions[$j]->mail_user) . "']];";

        echo "</script><button type=\"button\" class=\"btn btn-primary\" onclick=\"download_csv(data)\">Télécharger au format CSV</button>";
    }
}
echo '
    </div>
</div>

</div>
</div>

</div>

</div>
';
?>