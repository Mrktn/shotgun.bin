<?php

// Ici on est chargé de vérifier que l'argument shotgunId qu'on nous fournit dans le get est bien exploitable.

/* Si je suis user simple, je ne peux voir que les shotguns dont la date de publi est dépassée, qui sont ouverts ET actifs.
 * 
 * Si je suis admin, je peux tout voir, mais j'ai assez d'infos pour m'y retrouver quand même.
 */


require_once('classes/inscription.php');

if(!isset($_GET['idShotgun']))
    //header('Location: index.php?activePage=error&msg=Donnez le numéro du shotgun !');
    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Identifiant invalide !"));
        

if(!isset($_SESSION['mailUser']))
    //header('Location: index.php?activePage=error&msg=Utilisateur non enregistré !');
    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Il faut se connecter pour voir les shotguns !"));
    

if(!shotgun_event::userMayViewShotgunRecord(DBi::$mysqli, $_SESSION['mailUser'], $_GET['idShotgun'], $_SESSION['isAdmin']))
    //header('Location: index.php?activePage=error&msg=Accès interdit !');
    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Accès interdit, privilèges insuffisants !"));
    
// À ce stade on sait que le shotgun est dans la database.
$id = $_GET['idShotgun'];
$shotgun = shotgun_event::shotgunGet(DBi::$mysqli, $id);

// Est-ce que l'utilisateur courant est le créateur du shotgun considéré ?
$isCreateur = isset($_SESSION['mailUser']) && ($shotgun->mail_crea == $_SESSION['mailUser']);

/* if((!shotgun_event::shotgunIsVisible(DBi::$mysqli, $id) || shotgun_event::shotgunIsPerime(DBi::$mysqli, $id)) && !$isCreateur)
  header('Location: index.php?activePage=error&msg=Vous n\'avez pas les permissions pour voir ce shotgun !');
 */
$k = shotgun_event::getNumInscriptions(DBi::$mysqli, $id);
$n = $shotgun->nb_places;
// À ce stade on sait que l'utilisateur peut consulter le shotgun.

$button = '';
$frontNote = '';
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
        $frontNote .= ' (en attente de la réponse de l\'administrateur)';
}

elseif(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])
{
    if(shotgun_event::shotgunIsPerime(DBi::$mysqli, $shotgun->id))
        $frontNote = ' (évènement terminé)';
    else
    {
        if(!$shotgun->active)
        {
            $button = '<form action="index.php" method="get"><input type="hidden" name="todoShotgunIt" value="activateShotgun"><input type="hidden" name="activePage" value="shotgunRecord"><input type="hidden" name="idShotgun" value="' . $id . '"><input type="submit" value="Activer le shotgun" class="btn btn-primary"></form>';
        }
        else
        {
            $button = '<form action="index.php" method="get"><input type="hidden" name="todoShotgunIt" value="disableShotgun"><input type="hidden" name="activePage" value="shotgunRecord"><input type="hidden" name="idShotgun" value="' . $id . '"><input type="submit" value="Désactiver le shotgun" class="btn btn-primary"></form>';
        }
    }
}

// Sinon je suis l'user random
else
{
    // Si je suis inscrit, on me propose de me désinscrire
    if(inscription::userIsRegistered(DBi::$mysqli, $shotgun->id, $_SESSION['mailUser']))
        $button = '<a href="index.php?activePage=shotgunIt&todoShotgunIt=unsuscribe&idShotgun=' . $shotgun->id . '" class="btn btn-danger" unsuscribe-confirm="Êtes-vous certain de vouloir vous désinscrire ? :-O" role="button">Désinscription</a><br/>';

    // Sinon, de shotgun, à condition que la limite ne soit pas dépassée
    else
    {
        // Si c'est illimité, on écrit "s'inscrire" plutôt que "shotgun"
        if($n == 0)
            $button = '<form action="index.php" method="get"><input type="hidden" name="todoShotgunIt" value="suscribe"><input type="hidden" name="activePage" value="shotgunIt"><input type="hidden" name="idShotgun" value="' . $id . '"><input type="submit" value="S\'inscrire" class="btn btn-danger"></form>';
        
        // Sinon si c'est limité mais qu'il y a de la place
        elseif($k < $n)
        {
            $button = '<form action="index.php" method="get"><input type="hidden" name="todoShotgunIt" value="suscribe"><input type="hidden" name="activePage" value="shotgunIt"><input type="hidden" name="idShotgun" value="' . $id . '"><input idShotgun="'. $id .'" id="buttonShotgun" type="submit" value="Shoootgun !" class="btn btn-danger"></form>';
        }
        
        // En revanche, s'il n'y a pas de place...
        else
        {
            $button = '<form action="index.php" method="get"><input type="hidden" name="todoShotgunIt" value="suscribe"><input type="hidden" name="activePage" value="shotgunIt"><input type="hidden" name="idShotgun" value="' . $id . '"><input idShotgun="'. $id .'" id="buttonShotgun" type="submit" value="Pas de place :(" class="btn btn-danger" disabled></form>';
        }
    }
}

echo '
<div class="container">
    <header class="page-header">
    <h1 class="page-title">' . htmlspecialchars($shotgun->titre) . " $frontNote</h1>" . $button . '
    <small> <i class="fa fa-clock-o"></i> Ajouté le ' . utf8_encode(strftime("%d %B %Y &agrave; %H:%M", strtotime($shotgun->date_crea))) . ' par ' . stripTheMail($shotgun->mail_crea) . '</small>
  </header>
<div class="row">
  <div class="container" style="width:85%">
    <div class="panel panel-default">
      <div class="panel-heading resume-heading">
        <div class="row">
          <div class="col-lg-12">
            <div class="col-xs-12 col-sm-4">
              <figure>
                <img height="200" width="200" class="img-circle img-responsive img-thumbnail" alt="alt text" src="' . $shotgun->link_thumbnail . '" onerror="this.src=\'http://staging-us.armscor.com/assets/users/products/612/ria_m5_shotgun_mattnickel_12ga_edited-1.jpg\'">
              </figure>
            </div>

            <div class="col-xs-12 col-sm-8">
              <ul class="list-group">
                <li class="list-group-item"><strong>Auteur:</strong> ' . htmlspecialchars($shotgun->au_nom_de) . '</li>
                <li class="list-group-item"><strong>Date:</strong> le ' . utf8_encode(strftime("%d %B %Y &agrave; %H:%M", strtotime($shotgun->date_event))) . '</li>
                <li class="list-group-item">' . '<div class="row"> 
                <span class="col-sm-3"><strong >Effectifs:</strong> </span>';
echo '<div class="progress progress-shotgun" idShotgun="' . $shotgun->id . '">';
include('api/progressbar.php');
echo '</div>';

echo '</div></li>
                <li class="list-group-item"><strong>Prix: </strong>' . $shotgun->prix . '€ </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div style="margin:10px">
        <h3><strong>Description</strong></h3>
        <p>
         ' . nl2br(htmlspecialchars($shotgun->description)) .
 '</p><br/>';

if($isCreateur)
{
    echo "<h3><strong>Questions posées</strong></h3>";

    $allQuestions = question::getQuestions(DBi::$mysqli, $shotgun->id);

    echo "<ul>";

    foreach($allQuestions as $q)
    {
        echo "<li style=\"list-style-type:none \"><strong>" . htmlspecialchars($q->intitule) . "</strong>";

        if($q->type != question::$TYPE_REPONSELIBRE)
        {
            echo "<ul>";
            $reponses = reponse::getReponses(DBi::$mysqli, $q->id);
            $stylePuce = 'style="list-style-type:' . ($q->type == question::$TYPE_CHOIXMULTIPLE ? "square" : "circle" ) . '"';
            foreach($reponses as $r)
            {
                echo "<li>" . htmlspecialchars($r->intitule) . "</li>";
            }

            echo "</ul>";
        }

        echo "</li>";
    }

    echo "</ul>";

    echo "<br/>";
}
echo '<h3><strong>Liste des participants</strong></h3>
        ';

if($shotgun->anonymous && !($_SESSION['mailUser'] == $shotgun->mail_crea))
{
    echo '<p>L\'auteur du shotgun n\'a pas souhaité rendre la liste des participants visible !</p>';
}
else
{
    $arrayInscriptions = inscription::getInscriptionsIn(DBi::$mysqli, $shotgun->id);
    
    if(is_null($arrayInscriptions))
        redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Erreur inconnue, veuillez contacter un administrateur !"));

    $tableInscriptions = array();

    echo '<div style="margin:50px"><table style="margin-bottom:10px" id="oklm" class="dynamic table-fill">
               <thead class="dynamic">
                <tr  class="dynamic">
                  <th class="dynamic">Rang</th>
                  <th class="dynamic">Mail</th>
                </tr>
              </thead><tbody class="dynamic">';

    $i = 1;
    foreach($arrayInscriptions as $ins)
    {
        echo "<tr class=\"dynamic\"><td class=\"dynamic\">$i</td><td class=\"dynamic\">" . htmlspecialchars($ins->mail_user) . '</td></tr>';
        $i = $i + 1;
    }

    echo '</tbody></table><script type="text/javascript">$(\'#oklm\').dynatable();</script>';

    // Si je suis le créateur du shotgun j'ai le droit de télécharger le csv
    if((isset($_SESSION['mailUser']) && $_SESSION['mailUser'] == $shotgun->mail_crea))
    {
        $i = 1;
        $j = 0;

        $formattedheader = "['Rang','Date d\'inscription','Mail'";

        $allQuestions = question::getQuestions(DBi::$mysqli, $shotgun->id);

        foreach($allQuestions as $q)
            $formattedheader .= ",'" . addslashes(preg_replace('/\s/', ' ', htmlspecialchars($q->intitule))) . "'";

        $formattedheader .= "]";

        echo "<script type=\"text/javascript\">var data = [";
        for($j = 0; $j < count($arrayInscriptions); $j = $j + 1)
        {
            $newline = "['$i','" . preg_replace('/\s/', ' ', $arrayInscriptions[$j]->date_shotgunned) . "','" . preg_replace('/\s/', ' ', addslashes(htmlentities($arrayInscriptions[$j]->mail_user))) . "'";
            $currInscription = inscription::getComprehensiveInscription(DBi::$mysqli, $shotgun->id, $arrayInscriptions[$j]->mail_user);

            foreach($currInscription as $row)
            {
                $newline .= ", '" . preg_replace('/\s/', ' ', htmlspecialchars($row["question_type"] == question::$TYPE_REPONSELIBRE ? $row["texte"] : $row['intitule_reponses'], ENT_QUOTES | ENT_SUBSTITUTE, 'utf-8')) . "'";
            }
            $newline .= "]";
            if($j < (count($arrayInscriptions) - 1))
                $newline .= ",";
            echo $newline;
            $i = $i + 1;
        }

        echo "];";
        echo "</script><button type=\"button\" class=\"btn btn-primary\" onclick=\"download_csv($formattedheader, data)\">Télécharger au format CSV</button>  ";
    }

    if($_SESSION['mailUser'] == $shotgun->mail_crea || $_SESSION['isAdmin'] == true)
        echo '<a href="index.php?activePage=index&todoShotgunIt=deleteShotgun&idShotgun=' . $shotgun->id . '" class="btn btn-danger"  delete-confirm="Êtes-vous certain de vouloir supprimer ce shotgun ? :-O" role="button">Supprimer</a>';
//echo '<form action="index.php" method="get"><input type="hidden" name="todoShotgunIt" value="closeShotgun"><input type="hidden" name="activePage" value="shotgunRecord"><input type="hidden" name="idShotgun" value="' . $id . '"><input delete-confirm="Êtes-vous certain de vouloir supprimer ce shotgun ? :-O" type="submit" value="Supprimer" class="btn btn-danger"></form>';
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
