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

function displayShotgunList($mysqli, $shotguns)
{
    foreach($shotguns as $currShotgun)
    {
        echo '<div idShotgun="' . $currShotgun->id . '" class="panel panel-default center-block shotgunPanel" style="align:center; width: 80%">
  <div class="panel-heading">
    <h3 class="panel-title pull-left" style="text-align:center"><strong>' . htmlspecialchars(utf8_encode($currShotgun->titre)) . '</strong> par <i>' . htmlspecialchars(utf8_encode($currShotgun->au_nom_de)) . '</i></h3>
  <a href="index.php?activePage=shotgunRecord&idShotgun='.$currShotgun->id.'" class="btn btn-info pull-right" role="button">Fiche</a><div class="clearfix"></div>
  </div>
  <div class="panel-body">';
        $_GET['idShotgun'] = $currShotgun->id;
        
        echo '<div class="progress progress-shotgun" idShotgun="' . $currShotgun->id . '">';
        include('./api/progressbar.php');
        echo '</div>';
        echo  '</div><p class="readingmore">' . nl2br(htmlspecialchars(utf8_encode($currShotgun->description)));
        echo '</p></div>';
    }
}
function displayShotgunAVenir($mysqli, $shotguns){
    echo'<div class="container fiftycent">
  <h2>Les prochains shotguns à ne pas rater</h2>
  <p>Be on time!</p>            
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Organisateur</th>
        <th>Titre</th>
        <th>Date de l'."'évènement</th>
        <th >Début Shotgun</th>
                <th>Prix</th>
      </tr>
    </thead>
    <tbody>";
    foreach($shotguns as $currShotgun) {
           echo'<tr>
        <td>'.htmlspecialchars(utf8_encode($currShotgun->au_nom_de)).'</td>
        <td>'.htmlspecialchars(utf8_encode($currShotgun->titre)).'</td>
        <td>'.htmlspecialchars(utf8_encode($currShotgun->date_event)).'</td>
        <td>'.htmlspecialchars(utf8_encode($currShotgun->date_publi)).'</td>
        <td>'.htmlspecialchars(utf8_encode($currShotgun->prix)).'</td>
      </tr>';
    }
    echo'    </tbody>
  </table>
</div>';
}

function displayMonAgenda($mysqli, $shotguns){
        echo'<div class="container fiftycent">
  <h2>Mon agenda</h2>
  <p>Sois présent si tu ne veux pas te faire Balestrer!</p>            
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Organisateur</th>
        <th>Titre</th>
        <th>Date de l'."'évènement</th>
        <th>Prix</th>
        <th>Inscrits</th>
      </tr>
    </thead>
    <tbody>";
    foreach($shotguns as $currShotgun) { // Faire le truc du ? de pro
        $n = shotgun_event::getNumInscriptions($mysqli,$currShotgun->id);
        $nbplaces = htmlspecialchars(utf8_encode($currShotgun->nb_places));
           echo'<tr>
        <td>'.htmlspecialchars(utf8_encode($currShotgun->au_nom_de)).'</td>
        <td>'.htmlspecialchars(utf8_encode($currShotgun->titre)).'</td>
        <td>'.htmlspecialchars(utf8_encode($currShotgun->date_event)).'</td>
        <td>'.htmlspecialchars(utf8_encode($currShotgun->prix)).'</td>';
           if ($nbplaces == 0) { echo ($n.'/'.$nbplaces);} else { echo ($nbplaces);};
        echo'<td>'.$n.'/'.htmlspecialchars(utf8_encode($currShotgun->nb_places)).'</td> 
      </tr>';
    }
    echo'    </tbody>
  </table>
</div>';
}
?>
