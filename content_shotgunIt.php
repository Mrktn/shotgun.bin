<?php

//echo "Ici on tâche de vous faire shotgun l'évènement d'id : " . $_GET['idShotgun'];

function fieldName($idQuest, $idRep)
{
    return 'quest' . $idQuest . ';rep' . $idRep;
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

        var_dump($_POST);
        if(count($questions) == 0)
        {
            echo '<div class="alert alert-info">
  <strong>Bonne nouvelle !</strong> L\'auteur du shotgun n\'a pas souhaité poser de question. Vous pouvez appuyer sur le bouton "Shotgun!" pour terminer l\'inscription.
</div>';
        }
        else
        {
            echo '<form method="post" action="index.php?activePage=shotgunIt&todoShotgunIt=suscribe&idShotgun='. $idShot . '">'; ///////// ******* IL FAUT UNE METHOD ET RÉFLÉCHIR À L'ACTION *****////
            foreach($questions as $q)
            {
                echo '<div class="panel panel-primary  center-block" style="align:center">
                            <div class="panel-heading">
                              <h3 class="panel-title"><strong>' . utf8_encode($q->intitule) . '</strong></h3>
                            </div>
                            <div class="panel-body">';

                if($q->type == question::$TYPE_CHOIXMULTIPLE)
                {
                    $reponses = reponse::getReponses($mysqli, $q->id);
                    foreach($reponses as $rep)
                    {
                        echo '<input type="checkbox" name="quest' . $q->id . '[]" value="rep' . $rep->id . '" name="rep' . $rep->id . '"/> <label for="rep' . $rep->id . '">' . utf8_encode($rep->intitule) . '</label><br />';
                    }
                    //echo 'la question est multiple !';
                }
                else if($q->type == question::$TYPE_CHOIXUNIQUE)
                {
                    $reponses = reponse::getReponses($mysqli, $q->id);
                    foreach($reponses as $rep)
                    {
                        echo '<input type="radio" name="quest' . $q->id . '" value="rep' . $rep->id . '" name="rep' . $rep->id . '"/> <label for="rep' . $rep->id . '">' . utf8_encode($rep->intitule) . '</label><br />';
                    }
                }
                else // réponse libre !
                {
                    echo '<textarea placeholder="Votre réponse..." name="quest' . $q->id . '" class="form-control" rows="5" id="comment"></textarea>';
                }
                echo '
                            </div>
                        </div>';
            }

            echo '<button type="submit" class="btn btn-default">Submit</button>';


            /* Quand on clique sur le bouton, on renvoie tout à cette page avec $_POST['submitting'] = "true" de sorte qu'on sait
             * qu'on est en train d'envoyer les données du formulaire et pas seulement en train de le remplir */
            echo '<input type="hidden" name="submitting" id="hiddenField" value="true" />';
            echo '</form>';
        }
    }
    else
    {
        echo 'ouch';
        //header('Location: index.php?activePage=error&msg=Vous ne pouvez pas vous inscrire à ce shotgun !');
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
        
    }
    else
        header('Location: index.php?activePage=error&msg=Vous ne pouvez pas vous désinscrire de ce shotgun !');
}

echo '</div>';














//echo '<script type="text/javascript">function myConfirmation(){return \'\';} window.onbeforeunload = myConfirmation;</script>';