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

function generateProgressBar($k, $n)
{
    $perc = $k / (float) $n;

    return '
    <div class="progress" >
      <div class="progress-bar active ' . labelFromPercentage($perc) . '" role="progressbar" aria-valuenow="' . floor(100 * $perc) . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . floor(100 * $perc) . '%">
        <span><strong>' . $k . ' / ' . $n . '</strong></span>
      </div>
    </div>';
}

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
    if($isAdmin){echo 'luser est admin !!';
        return false;
    }

    $user = utilisateur::getUtilisateur($mysqli, $mail);
    $shotgun = shotgun_event::shotgunGet($mysqli, $idShot);

    if(!$shotgun->active)
        echo 'ouais il est inactif';
    if(!$shotgun->ouvert)
        echo 'il est pas ouvert';
    if($mail == $shotgun->mail_crea)
        echo 'mais tes le crea coco';
    if((date('Y-m-d G:i:s') < $shotgun->date_publi))
        echo "ouais mais il est pas encore publié";
    if((date('Y-m-d G:i:s') > $shotgun->date_event))
        echo "la date est dépassée !" . date('Y-m-d G:i:s');
    if(!$shotgun->active || !$shotgun->ouvert || ($mail == $shotgun->mail_crea) || (date('Y-m-d G:i:s') < $shotgun->date_publi) || (date('Y-m-d G:i:s') > $shotgun->date_event) || inscription::userIsRegistered($mysqli, $idShot, $mail))
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

    $user = utilisateur::getUtilisateur($mysqli, $mail);
    $shotgun = shotgun_event::shotgunGet($mysqli, $idShot);

    if(!$shotgun->active || !$shotgun->ouvert || ($mail == $shotgun->mail_crea) || (date('Y-M-D G:i:s') < $shotgun->date_publi) || (date('Y-M-D G:i:s') > $shotgun->date_event) || !inscription::userIsRegistered($mysqli, $idShot, $mail))
        return false;

    return true;
}

?>