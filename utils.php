<?php

// antoine.balestrat@polytechnique.edu -> antoine.balestrat
function stripTheMail($mail)
{
    return explode("@", $mail)[0];
}

function labelFromPercentage($r)
{
    if ($r < 0.5)
        return "progress-bar-success";
    if ($r < 0.8)
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
    if ($isAdmin)
    {
        return false;
    }

    // Il doit être visible, aka ouvert, actif, et de date de publi (programmée) dépassée
    // Et pas périmé, aka la date de l'évènement n'est pas dépassée.
    if (!shotgun_event::shotgunIsVisible($mysqli, $idShot) || shotgun_event::shotgunIsPerime($mysqli, $idShot))
        return false;

    $shotgun = shotgun_event::shotgunGet($mysqli, $idShot);

    // Si l'utilisateur est le créateur ou qu'il est déjà enregistré, c'est aussi interdit...
    if (($mail == $shotgun->mail_crea) || inscription::userIsRegistered($mysqli, $idShot, $mail))
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
    if ($isAdmin)
        return false;

    // Il doit être visible, aka ouvert, actif, et de date de publi (programmée) dépassée
    // Et pas périmé, aka la date de l'évènement n'est pas dépassée.
    if (!shotgun_event::shotgunIsVisible($mysqli, $idShot) || shotgun_event::shotgunIsPerime($mysqli, $idShot))
        return false;

    $shotgun = shotgun_event::shotgunGet($mysqli, $idShot);

    if (($mail == $shotgun->mail_crea) || !inscription::userIsRegistered($mysqli, $idShot, $mail))
        return false;

    return true;
}

function displayShotgunList($mysqli, $shotguns, $mailUser)
{
    foreach ($shotguns as $currShotgun)
    {
        $isCreateur = $currShotgun->mail_crea == $mailUser;
        $hasShotgunned = inscription::userIsRegistered($mysqli, $currShotgun->id, $mailUser);

        echo '<div idShotgun="' . $currShotgun->id . '" class="panel panel-default center-block shotgunPanel" style="align:center; width: 80%">
  <div class="panel-heading"><p style="float:left;">';

        if ($hasShotgunned)
            echo '<span title="Vous avez shotgun cet évènement !" style="font-size:18px" class="glyphicon glyphicon-ok"></span>   ';

        if ($isCreateur)
            echo '<span title="Vous êtes l\'auteur de ce shotgun" style="font-size:18px" class="glyphicon glyphicon-user"></span>   ';

        // Si pas autorisé par l'admin
        if (!$currShotgun->active)
            echo '<span title="Shotgun en attente de révision par l\'administrateur" style="font-size:18px" class="glyphicon glyphicon-minus-sign"></span>   ';

        if (!$currShotgun->ouvert)
            echo '<span title="Vous devez ouvrir ce shotgun pour qu\'il puisse apparaître dans la liste publique" style="font-size:18px" class="glyphicon glyphicon-lock"></span>   ';

        echo'</p><h3 class="panel-title pull-left" style="text-align:center"><strong>' . htmlspecialchars($currShotgun->titre) . '</strong> par <i>' . htmlspecialchars($currShotgun->au_nom_de) . '</i></h3>
  <a href="index.php?activePage=shotgunRecord&idShotgun=' . $currShotgun->id . '" class="btn btn-info pull-right" role="button">Fiche</a><div class="clearfix"></div>
  </div>
  <div class="panel-body">';
        $_GET['idShotgun'] = $currShotgun->id;

        echo '<div class="progress progress-shotgun" idShotgun="' . $currShotgun->id . '">';
        include('./api/progressbar.php');
        echo '</div>';
        echo '</div><p class="readingmore">' . nl2br(htmlspecialchars($currShotgun->description));
        echo '</p></div>';
    }
}

function displayShotgunAVenir($mysqli, $shotguns)
{
    echo'<div class="container fiftycent">
  <h2>Prochains shotguns à ne pas rater</h2>';
    if (count($shotguns) == 0)
    {
        echo"<p> Aucun évènement n'est prévu pour l'instant. N'hésite pas à en créer un.s";
    } else
    {
        echo'
  <p>Tiens-toi prêt!</p>            
  <table class="table table-hover dynamic table-fill tableaccueil">
    <thead>
      <tr>
        <th class="thaccueil">Organisateur</th>
        <th class="thaccueil">Titre</th>
        <th class="thaccueil">Date</th>
        <th class="thaccueil" >Shotgun</th>
                <th class="thaccueil">Prix</th>
      </tr>
    </thead>
    <tbody>';
        $compteur = 0;
        foreach ($shotguns as $currShotgun)
        {
            if ($compteur < 5)
            {
                echo'<tr>
        <td>' . htmlspecialchars($currShotgun->au_nom_de) . '</td>
        <td>' . htmlspecialchars($currShotgun->titre) . '</td>
        <td>' . htmlspecialchars($currShotgun->date_event) . '</td>
        <td>' . htmlspecialchars($currShotgun->date_publi) . '</td>
        <td>' . $currShotgun->prix . '€</td>
      </tr>';
                $compteur = $compteur + 1;
            }
        }
        echo'    </tbody>
  </table>
</div>';
    }
}

function displayMonAgenda($mysqli, $shotguns)
{ // Affiche les 5 plus proches évènements sur la page d'accueil
    echo'<div class="container fiftycent">
  <h2>Mon agenda</h2>';
    if (count($shotguns) == 0)
    {
        echo"<p> Tu n'as actuellement rien shotgun. <br/> Inscris-toi vite!</p>";
    } else
    {
        echo'
  <p>Voici la liste de tes prochains évènements. Sois présent!</p>            
  <table class="table table-hover dynamic table-fill tableaccueil">
    <thead>
      <tr>
        <th class="thaccueil">Organisateur</th>
        <th class="thaccueil">Titre</th>
        <th class="thaccueil">Date</th>
        <th class="thaccueil">Prix</th>
        <th class="thaccueil">Inscrits</th>
      </tr>
    </thead>
    <tbody>';
        $compteur = 0;
        foreach ($shotguns as $currShotgun)
        { // Faire le truc du ? de pro
            if ($compteur < 5)
            {
                $n = shotgun_event::getNumInscriptions($mysqli, $currShotgun->id);
                $nbplaces = $currShotgun->nb_places;
                echo'<tr>
        <td>' . htmlspecialchars($currShotgun->au_nom_de) . '</td>
        <td>' . htmlspecialchars($currShotgun->titre) . '</td>
        <td>' . htmlspecialchars($currShotgun->date_event) . '</td>
        <td>' . $currShotgun->prix . '€</td>';
                echo'<td>';
                if ($nbplaces != 0)
                {
                    echo ($n . '/' . $nbplaces);
                } else
                {
                    echo ($n);
                };
                echo'</td>
      </tr>';
                $compteur = $compteur + 1;
            }
        }
        echo'    </tbody>
  </table>
</div>';
    }
}

// Si on set preheader, c'est que la fonction est appelée avant un include de content
// Du coup, comme on est encore en train de parser la variable $_GET pour savoir si l'userinput est valide
// on n'a encore rien envoyé. On ajoute donc les balises nécessaires pour que le navigateur ait de quoi travailler
function redirectWithPost($url, $arrpost, $preheader = false)
{
    if ($preheader)
    {
        echo '<!DOCTYPE html>
                <html>
                    <head>
                        <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
                    </head>
                    <body>';
    }
    echo '<script type="text/javascript">function redirectPost(url, data) {
    var form = document.createElement("form");
    form.method = "post";
    form.action = url;
    for(var name in data) {
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = name;
        input.value = data[name];
        form.appendChild(input);
    }
    form.submit();
}; redirectPost("' . $url . '", {"placeholder":null';
    foreach ($arrpost as $key => $val)
        echo ',"' . $key . '":"' . $val . '"';
    echo '});</script>';

    if ($preheader)
        echo '</body></html>';
}
?>

