<?php

//echo "Ici on tâche de vous faire shotgun l'évènement d'id : " . $_GET['idShotgun'];

function checkRas($type, $nquest, $rep)
{
    global $mysqli;
    if(!ctype_digit($nquest) || ($type == "m" && !is_array($rep)) || false)
        header('Location: index.php?activePage=error&msg=Requête d\'inscription mal formée !');

    if($type == "u" || $type == "f")
    {
        $pieces = explode("-", $rep);
        if(!ctype_digit($pieces[1]) || !reponse::repIsValid($mysqli, intval($nquest), intval($pieces[1])))
            header('Location: index.php?activePage=error&msg=Requête d\'inscription mal formée !');
    }

    elseif($type == "m")
    {
        foreach($rep as $vrep)
        {
            $pieces = explode("-", $vrep);

            if(!ctype_digit($pieces[1]) || !reponse::repIsValid($mysqli, intval($nquest), intval($pieces[1])))
                header('Location: index.php?activePage=error&msg=Requête d\'inscription mal formée !');
        }
    }
}

$idShot = $_GET['idShotgun'];

if(!isset($_GET['todoShotgunIt']))
    header('Location: index.php?activePage=error&msg=Vous ne pouvez pas vous inscrire à ce shotgun !');

if(!shotgun_event::shotgunIsInDB($mysqli, $idShot))
    header('Location: index.php?activePage=error&msg=Ce shotgun n\'existe pas !');

if($_GET['todoShotgunIt'] == 'suscribe')
{
    echo '<div class="container">
<div class="alert alert-warning center-block">
  <strong>Attention !</strong> ' . "En vous inscrivant, vous vous engagez :<br/><ul><li>À régler la somme de n€ à l'organisateur ()</li><li>À participer à l'évènement, qui aura lieu le [date]</li></ul><br/>Vous conserverez le droit de vous désinscrire, mais les organisateurs garderont un historique détaillé des inscriptions. Conduisez-vous de manière responsable !<br/>Après inscriptions, vos informations ne seront plus modifiables. Si vous souhaitez modifier votre inscription, il vous faudra d'abord vous désinscrire et refaire un shotgun." .
    '</div>';
    // pas un admin
    // pas le créateur
    // open et active
    // pas périmé
    // publié
    // pas déjà inscrit
    if(userMaySuscribe($mysqli, $idShot, $_SESSION['isAdmin'], $_SESSION['mailUser']))
    {
        $shotgun = shotgun_event::shotgunGet($mysqli, $idShot);
        $questions = question::getQuestions($mysqli, $idShot);

        // On a donc submitted les réponses.
        if(isset($_POST['submitting']) && $_POST['submitting'] == "true")
        {
            // Ici on va envoyer une requête pour inscrire le pax.
            $formattedArray = array();

            foreach($_POST as $q => $r)
            {
                if($q != "submitting")
                {
                    // FIXME: checker que tout se passe bien ici
                    $pieces = explode("-", $q);

                    if($pieces[0] == "uquest")
                    {
                        checkRas("u", $pieces[1], $r);

                        $repieces = explode("-", $r);
                        $formattedArray[intval($pieces[1])][] = array(intval($repieces[1]), "");

                        //echo "Ok donc la question `{$pieces[1]}` prend pour réponse {$repieces[1]}<br/>";
                    }
                    elseif($pieces[0] == "mquest")
                    {
                        checkRas("m", $pieces[1], $r);

                        foreach($r as $vrep)
                        {
                            $repieces = explode("-", $vrep);
                            $formattedArray[intval($pieces[1])][] = array(intval($repieces[1]), "");

                            //echo "Ok donc la question `{$pieces[1]}` prend pour réponse {$repieces[1]}<br/>";
                        }
                    }
                    elseif($pieces[0] == "fquest")
                    {
                        checkRas("f", $pieces[1], $r[0]);

                        $repieces = explode("-", $r[0]);

                        $formattedArray[intval($pieces[1])][] = array(intval($repieces[1]), $r[1]);
                    }
                    else
                        header("Location: index.php?activePage=error&msg=Requête d'inscription mal formée !");
                }
            }

            /* echo "<br/><br/><br/><pre>";

              var_dump($formattedArray);
              echo "</pre>"; */

            if(inscription::doInscription($mysqli, $idShot, $_SESSION['mailUser'], $formattedArray))
                header("Location: index.php?activePage=shotgunRecord&idShotgun=$idShot");
            else
                header("Location: index.php?activePage=error&msg=Impossible de vous inscrire à l'évènement \"" . htmlspecialchars($shotgun->titre) . "\" !");
            // $idShot est déjà vérifié plus que de raison
            // $user est récupéré dans la session courante donc safe
            // $answers est associatif $idquestion => array{[$idréponse, $texte si libre]} et déjà vérifié
            // public static function doInscription($mysqli, $idShot, $mailUser, $answers)
        }
























        // Sinon on est en train d'afficher le formulaire
        else
        {
            if(count($questions) == 0)
            {
                echo '<div class="alert alert-info">
  <strong>Bonne nouvelle !</strong> L\'auteur du shotgun n\'a pas souhaité poser de question. Vous pouvez appuyer sur le bouton "Shotgun!" pour terminer l\'inscription.
</div>';
            }
            else
            {
                echo '<form data-toggle="validator" method="post" action="index.php?activePage=shotgunIt&todoShotgunIt=suscribe&idShotgun=' . $idShot . '">'; ///////// ******* IL FAUT UNE METHOD ET RÉFLÉCHIR À L'ACTION *****////
                foreach($questions as $q)
                {
                    echo '<div class="panel panel-primary  center-block" style="align:center">
                            <div class="panel-heading">
                              <h3 class="panel-title"><strong>' . utf8_encode($q->intitule) . '</strong></h3>
                            </div>
                            <div class="panel-body"><div class="form-group">';

                    if($q->type == question::$TYPE_CHOIXMULTIPLE)
                    {
                        $reponses = reponse::getReponses($mysqli, $q->id);
                        foreach($reponses as $rep)
                        {
                            echo '<input type="checkbox" name="mquest-' . $q->id . '[]" value="rep-' . $rep->id . '" value="rep' . $rep->id . '"/> <label for="rep' . $rep->id . '">' . utf8_encode($rep->intitule) . '</label><br />';
                        }
                    }
                    else if($q->type == question::$TYPE_CHOIXUNIQUE)
                    {
                        $reponses = reponse::getReponses($mysqli, $q->id);
                        foreach($reponses as $rep)
                        {
                            echo '<input type="radio" name="uquest-' . $q->id . '" value="rep-' . $rep->id . '" value="rep' . $rep->id . '" required/> <label for="rep' . $rep->id . '">' . utf8_encode($rep->intitule) . '</label><br />';
                        }
                    }
                    else // réponse libre !
                    {
                        $reponses = reponse::getReponses($mysqli, $q->id);
                        echo '<input type="hidden" name="fquest-' . $q->id . '[]" id="hiddenField" value="rep-' . $reponses[0]->id . '" />';
                        echo '<textarea placeholder="Votre réponse..." name="fquest-' . $q->id . '[]" value="testomg" class="form-control" rows="5" id="comment"></textarea>';
                    }
                    echo ' </div>
                            </div>
                        </div>';
                }

                echo '<button type="submit" class="btn btn-default">Envoyer</button>';


                /* Quand on clique sur le bouton, on renvoie tout à cette page avec $_POST['submitting'] = "true" de sorte qu'on sait
                 * qu'on est en train d'envoyer les données du formulaire et pas seulement en train de le remplir */
                echo '<input type="hidden" name="submitting" id="hiddenField" value="true" />';
                echo '</form>';
            }
        }
    }
    else
    {
        header('Location: index.php?activePage=error&msg=Vous ne pouvez pas vous inscrire à ce shotgun !');
    }
}

// C'est forcément unsuscribe, on l'a déjà testé dans l'index
else
{
    // pas un admin
    // pas le créateur
    // pas périmé
    // publié
    // open et active
    // déjà inscrit
    if(userMayUnsuscribe($mysqli, $idShot, $_SESSION['isAdmin'], $_SESSION['mailUser']))
    {
        $shotgun = shotgun_event::shotgunGet($mysqli, $idShot);

        if(inscription::doDesinscription($mysqli, $idShot, $_SESSION['mailUser']))
            header('Location: index.php?activePage=shotgunRecord&idShotgun=' . $shotgun->id);
        else
            header('Location: index.php?activePage=error&msg=Impossible de vous désinscrire de ce shotgun !');
    }
    else
    {
        header('Location: index.php?activePage=error&msg=Vous ne pouvez pas vous désinscrire de ce shotgun !');
    }
}

echo '</div>';














//echo '<script type="text/javascript">function myConfirmation(){return \'\';} window.onbeforeunload = myConfirmation;</script>';