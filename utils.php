<?php

// antoine.balestrat@polytechnique.edu -> antoine.balestrat
function stripTheMail($mail)
{
    return explode("@", $mail)[0];
}

function labelFromPercentage($r)
{
    if($r < 0.5)
        return "progress-bar-success";
    if($r < 0.8)
        return "progress-bar-warning";

    return "progress-bar-danger";
}

/* function generateProgressBarFromShotgun($mysqli, $shotgun)
  {
  // Maintenant on claque une petite requête pour savoir combien il y a d'inscriptions à ce shotgun pour l'instant.
  $k = shotgun_event::getNumInscriptions($mysqli, $shotgun->id);
  $n = $shotgun->nb_places;

  $perc = $k / (float) $n;

  return '
  <div class="progress progress-shotgun" idShot="' . $shotgun->id . '">
  <div class="progress-bar active ' . labelFromPercentage($perc) . '" role="progressbar" aria-valuenow="' . floor(100 * $perc) . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . floor(100 * $perc) . '%">
  <span><strong>' . $k . ' / ' . $n . '</strong></span>
  </div>
  </div>';
  }

  function generateProgressBarFromId($mysqli, $id)
  {
  $shotgun = shotgun_event::shotgunGet($mysqli, $id);
  return generateProgressBarFromShotgun($mysqli, $shotgun);
  } */

// TODO: cette fonction, mieux écrite, nous fera gagner en sécurité
function isValidPolytechniqueEmail($mail)
{
    return preg_match("/.+@polytechnique\.edu/", $mail);
}

// pas un admin
// pas le créateur
// open et active
// pas périmé
// publié
// pas déjà inscrit
function userMaySuscribe($mysqli, $idShot, $isAdmin, $mail)
{
    // L'administrateur ne peut pas shotgunner
    if($isAdmin)
    {
        return false;
    }

    // Il doit être visible, aka ouvert, actif, et de date de publi (programmée) dépassée
    // Et pas périmé, aka la date de l'évènement n'est pas dépassée.
    if(!shotgun_event::shotgunIsVisible($mysqli, $idShot) || shotgun_event::shotgunIsPerime($mysqli, $idShot))
        return false;

    $shotgun = shotgun_event::shotgunGet($mysqli, $idShot);

    // Si l'utilisateur est le créateur ou qu'il est déjà enregistré, c'est aussi interdit...
    if(($mail == $shotgun->mail_crea) || inscription::userIsRegistered($mysqli, $idShot, $mail))
        return false;

    return true;
}

// pas un admin
// pas le créateur
// pas périmé
// publié
// open et active
// inscrit
function userMayUnsuscribe($mysqli, $idShot, $isAdmin, $mail)
{
    if($isAdmin)
        return false;

    // Il doit être visible, aka ouvert, actif, et de date de publi (programmée) dépassée
    // Et pas périmé, aka la date de l'évènement n'est pas dépassée.
    if(!shotgun_event::shotgunIsVisible($mysqli, $idShot) || shotgun_event::shotgunIsPerime($mysqli, $idShot))
        return false;

    $shotgun = shotgun_event::shotgunGet($mysqli, $idShot);

    if(($mail == $shotgun->mail_crea) || !inscription::userIsRegistered($mysqli, $idShot, $mail))
        return false;

    return true;
}
function doCreateShotgun($mysqli){
if (isset($_POST["titre"]) && $_POST["titre"] != "" &&
        isset($_POST["description"]) && $_POST["description"] != "" &&
        isset($_POST["mail_crea"]) && $_POST["mail_crea"] != "" &&
        isset($_POST["au_nom_de"]) && $_POST["au_nom_de"] != "" &&
        isset($_POST["date_event"]) && $_POST["date_event"] != "" &&
        isset($_POST["date_publi"]) && $_POST["date_publi"] != "" &&
        isset($_POST["anonymous"]) && gettype($_POST["anonymous"]) == "integer")
{
    shotgun_event::traiteShotgunForm($mysqli);
    $idShotgun = $stmt->insert_id;
    $stmt->close();
    $nQuest = $_POST['intitule'] . length; // Nombre de questions
    for ($i = 0; $i < $nQuest; $i++)
    { // Traitons la question i 
        question::traiteQuestionForm($mysqli, $idShotgun, $i); // Insertion de la question
        $idQuestion = $stmt->insert_id;
        $stmt->close();
        if ($_POST['typeReponse'+$i] != 2){
        traiteChoixForm($mysqli, $idQuestion, $i);
        }
    }
}
}

function displayShotgunList($mysqli, $shotguns)
{
    foreach($shotguns as $currShotgun)
    {
        echo '<div idShotgun="' . $currShotgun->id . '" class="panel panel-default center-block shotgunPanel" style="align:center; width: 80%">
  <div class="panel-heading">
    <h3 class="panel-title" style="text-align:center"><strong>' . htmlspecialchars(utf8_encode($currShotgun->titre)) . '</strong> par <i>' . htmlspecialchars(utf8_encode($currShotgun->au_nom_de)) . '</i></h3>
  </div>
  <div class="panel-body">';
        $_GET['idShotgun'] = $currShotgun->id;
        
        echo '<div class="progress progress-shotgun" idShotgun="' . $currShotgun->id . '">';
        include('progressbar.php');
        echo '</div>';
        echo  '</div>' . nl2br(htmlspecialchars(utf8_encode($currShotgun->description)));
        echo '</div></div>';
    }
}

?>
