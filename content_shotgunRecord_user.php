<?php

require_once('inscription.php');

if(!isset($_GET['idShotgun']) || !shotgun_event::shotgunIsInDB($mysqli, $_GET['idShotgun']))
    header('Location: index.php?activePage=error&msg=Impossible d\'afficher ce shotgun !');

// À ce stade on sait que le shotgun est dans la database.
$id = $_GET['idShotgun'];
$shotgun = shotgun_event::shotgunGet($mysqli, $id);

// Est-ce que l'utilisateur courant est le créateur du shotgun considéré ?
$isCreateur = isset($_SESSION['mailUser']) && ($shotgun->mail_crea == $_SESSION['mailUser']);

if(!shotgun_event::shotgunIsVisible($mysqli, $id) || shotgun_event::shotgunIsPerime($mysqli, $id) && !$isCreateur)
    header('Location: index.php?activePage=error&msg=Vous n\'avez pas les permissions pour voir ce shotgun !');
$k = shotgun_event::getNumInscriptions($mysqli, $id);
$n = $shotgun->nb_places;
// À ce stade on sait que l'utilisateur peut consulter le shotgun.

$button = '';
$note = ''; // frontNote = '(en attente de la réponse de l'administrateur)' si c'est le cas
// Si je suis le créateur...
if($isCreateur)
{
    // ... et que le shotgun est ouvert
    if($shotgun->ouvert)
    {
        // On propose de fermer le shotgun
        $button = '<form action="index.php" method="get"><input type="hidden" name="todoShotgunIt" value="closeShotgun"><input type="hidden" name="activePage" value="shotgunRecord"><input type="hidden" name="idShotgun" value="' . $id . '"><input type="submit" value="Fermer le shotgun" class="btn btn-primary"></form>';
    }
    else
    {
        // Sinon, de l'ouvrir
        $button = '<form action="index.php" method="get"><input type="hidden" name="todoShotgunIt" value="openShotgun"><input type="hidden" name="activePage" value="shotgunRecord"><input type="hidden" name="idShotgun" value="' . $id . '"><input type="submit" value="Ouvrir le shotgun" class="btn btn-primary"></form>';
    }

    if(!$shotgun->active)
        $frontNote = '(en attente de la réponse de l\'administrateur)';
}

// Sinon je suis l'user random
else
{
    // Si je suis inscrit, on me propose de me désinscrire
    if(inscription::userIsRegistered($mysqli, $shotgun->id, $_SESSION['mailUser']))
        $button = '<form action="index.php" method="get"><input type="hidden" name="todoShotgunIt" value="unsuscribe"><input type="hidden" name="activePage" value="shotgunIt"><input type="hidden" name="idShotgun" value="' . $id . '"><input type="submit" value="Désinscription" class="btn btn-danger"></form>';
    // Sinon, de shotgun
    else
        $button = '<form action="index.php" method="get"><input type="hidden" name="todoShotgunIt" value="suscribe"><input type="hidden" name="activePage" value="shotgunIt"><input type="hidden" name="idShotgun" value="' . $id . '"><input type="submit" value="Shoootgun!" class="btn btn-danger"></form>';
}


echo '
<div class="container">
<div>
    <header class="page-header">
    <h1 class="page-title">' . utf8_encode($shotgun->titre) . ' ' . $button . '</h1>
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

    // Si je suis le créateur du shotgun j'ai le droit de télécharger le csv
    if((isset($_SESSION['mailUser']) && $_SESSION['mailUser'] == $shotgun->mail_crea))
    {
        $i = 1;
        $j = 0;

        $formattedheader = "['Rang','Date d\'inscription','Mail'";

        $allQuestions = question::getQuestions($mysqli, $shotgun->id);

        foreach($allQuestions as $q)
        {
            $formattedheader .= ",'" . addslashes(htmlspecialchars(utf8_encode($q->intitule))) . "'";
        }
        
        $formattedheader .= "]";

        echo "<script type=\"text/javascript\">var data = [";
        for($j = 0; $j < count($arrayInscriptions); $j = $j + 1)
        {
            $newline = "['$i','" . $arrayInscriptions[$j]->date_shotgunned . "','" . addslashes(htmlentities($arrayInscriptions[$j]->mail_user)) . "'";
            $currInscription = inscription::getComprehensiveInscription($mysqli, $shotgun->id, $arrayInscriptions[$j]->mail_user);
            
            foreach($currInscription as $row)
            {
                $newline .= ", '" . htmlspecialchars($row["question_type"] == question::$TYPE_REPONSELIBRE ? $row["texte"] : $row['intitule_reponses'],ENT_QUOTES | ENT_SUBSTITUTE, 'utf-8') . "'";
            }
            $newline .= "]";
            if($j < (count($arrayInscriptions) - 1))
                $newline .= ",";
            echo $newline;
            $i = $i + 1;
        }
        
        echo "];";
        echo "</script><button type=\"button\" class=\"btn btn-primary\" onclick=\"download_csv($formattedheader, data)\">Télécharger au format CSV</button>";
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