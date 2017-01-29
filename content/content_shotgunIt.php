<?php

function checkRas($type, $nquest, $rep)
{
    if(!ctype_digit($nquest) || ($type == "m" && !is_array($rep)) || false)
        //header('Location: index.php?activePage=error&msg=Requête d\'inscription mal formée !');
        redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Requête d'inscription mal formée !"));
     

    if($type == "u" || $type == "f")
    {
        $pieces = explode("-", $rep);
        if(!ctype_digit($pieces[1]) || !reponse::repIsValid(DBi::$mysqli, intval($nquest), intval($pieces[1])))
            //header('Location: index.php?activePage=error&msg=Requête d\'inscription mal formée !');
                redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Requête d'inscription mal formée !"));
    }

    elseif($type == "m")
    {
        foreach($rep as $vrep)
        {
            $pieces = explode("-", $vrep);

            if(!ctype_digit($pieces[1]) || !reponse::repIsValid(DBi::$mysqli, intval($nquest), intval($pieces[1])))
                //header('Location: index.php?activePage=error&msg=Requête d\'inscription mal formée !');
                    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Requête d'inscription mal formée !"));
        }
    }
}

$idShot = $_GET['idShotgun'];

if(!isset($_GET['todoShotgunIt']))
    //header('Location: index.php?activePage=error&msg=Vous ne pouvez pas vous inscrire à ce shotgun !');
    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Vous ne pouvez pas vous inscrire à ce shotgun !"));

if(!shotgun_event::shotgunIsInDB(DBi::$mysqli, $idShot))
    //header('Location: index.php?activePage=error&msg=Ce shotgun n\'existe pas !');
    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Ce shotgun n'existe pas !"));

if($_GET['todoShotgunIt'] == 'suscribe')
{
    // pas un admin
    // pas le créateur
    // open et active
    // pas périmé
    // publié
    // pas déjà inscrit
    if(userMaySuscribe(DBi::$mysqli, $idShot, $_SESSION['isAdmin'], $_SESSION['mailUser']))
    {
        $shotgun = shotgun_event::shotgunGet(DBi::$mysqli, $idShot);
        $questions = question::getQuestions(DBi::$mysqli, $idShot);
        $dateIntelligible = utf8_encode(strftime("%d %B %Y", strtotime($shotgun->date_event))). " à " . utf8_encode(strftime("%Hh%M", strtotime($shotgun->date_event)));
        echo '<div class ="container-fluid titlepage" > <h1>Shotgun de l\'évènement</h1> </div><br/><br/> <div class="container">
<div class="alert alert-warning center-block">
  <strong>Attention !</strong> ' . "En vous inscrivant, vous vous engagez :<br/><ul>" . ($shotgun->prix != 0 ? ("<li>À <b>régler la somme de {$shotgun->prix}€</b> à \"{$shotgun->au_nom_de}\"</li>") : ("")) . "<li>À participer à l'évènement, qui aura lieu le <b>$dateIntelligible</b></li></ul><br/>Vous conserverez le droit de vous désinscrire, mais les organisateurs garderont un historique détaillé des inscriptions. Conduisez-vous de manière responsable !<br/>Après inscription, vos informations ne seront plus modifiables. Si vous souhaitez modifier votre inscription, il vous faudra d'abord vous désinscrire et refaire un shotgun, <b>au risque de perdre votre place dans cet intervalle de temps</b> !" .
    '</div><br/><br/>';

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
                    }
                    elseif($pieces[0] == "mquest")
                    {
                        checkRas("m", $pieces[1], $r);

                        foreach($r as $vrep)
                        {
                            $repieces = explode("-", $vrep);
                            $formattedArray[intval($pieces[1])][] = array(intval($repieces[1]), "");
                        }
                    }
                    elseif($pieces[0] == "fquest")
                    {
                        checkRas("f", $pieces[1], $r[0]);

                        $repieces = explode("-", $r[0]);

                        $formattedArray[intval($pieces[1])][] = array(intval($repieces[1]), $r[1]);
                    }
                    else
                        //header("Location: index.php?activePage=error&msg=Requête d'inscription mal formée !");
                        redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Requête d'inscription mal formée !"));
                }
            }

            if(inscription::doInscription(DBi::$mysqli, $idShot, $_SESSION['mailUser'], $formattedArray))
                redirectWithPost("index.php?activePage=shotgunRecord&idShotgun=$idShot", array('tip' => 'success', 'msg' => "Inscription réussie !"));
            else
                redirectWithPost("index.php?activePage=shotgunRecord&idShotgun=$idShot", array('tip' => 'error', 'msg' => "Shotgun raté :'("));
        }
























        // Sinon on est en train d'afficher le formulaire
        else
        {
            if(count($questions) == 0)
            {
                echo '<div class="alert alert-info">
  <strong>Bonne nouvelle !</strong> L\'auteur du shotgun n\'a pas souhaité poser de question. Vous pouvez appuyer sur le bouton "Confirmer" pour terminer l\'inscription.
</div>';
                echo '<form method="post" action="index.php?activePage=shotgunIt&todoShotgunIt=suscribe&idShotgun=' . $idShot . '">';
                
                echo '<button id="sendShotgunButton" idShotgun="'.$idShot.'" type="submit" class="btn btn-primary btn-lg btn-block">Envoyer</button>';
                echo '<input type="hidden" name="submitting" value="true" />';
                echo '</form>';
            }
            else
            {
                echo '<form data-toggle="validator" method="post" action="index.php?activePage=shotgunIt&todoShotgunIt=suscribe&idShotgun=' . $idShot . '">';
                foreach($questions as $q)
                {
                    echo '<div class="panel panel-primary center-block" style="align:center">
                            <div class="panel-heading">
                              <h3 style="font-size: 1vw" class="panel-title"><strong>' . htmlspecialchars($q->intitule) . '</strong></h3>
                            </div>
                            <div class="panel-body">';

                    if($q->type == question::$TYPE_CHOIXMULTIPLE)
                    {
                        echo '<div class="form-group multiple_choices_form">';
                        $reponses = reponse::getReponses(DBi::$mysqli, $q->id);
                        foreach($reponses as $rep)
                        {
                            echo '<input type="checkbox" id="rep' . $rep->id . '" name="mquest-' . $q->id . '[]" value="rep-' . $rep->id . '" required/> <label for="rep' . $rep->id . '">' . htmlspecialchars($rep->intitule) . '</label><br />';
                        }
                        echo '</div>';
                    }
                    else if($q->type == question::$TYPE_CHOIXUNIQUE)
                    {
                        echo '<div class="form-group">';
                        $reponses = reponse::getReponses(DBi::$mysqli, $q->id);
                        foreach($reponses as $rep)
                        {
                            echo '<input type="radio" id="rep' . $rep->id . '" name="uquest-' . $q->id . '" value="rep-' . $rep->id . '"  required/> <label for="rep' . $rep->id . '">' . htmlspecialchars($rep->intitule) . '</label><br />';
                        }
                        echo '</div>';
                    }
                    else // réponse libre !
                    {
                        echo '<div class="form-group">';
                        $reponses = reponse::getReponses(DBi::$mysqli, $q->id);
                        echo '<input type="hidden" name="fquest-' . $q->id . '[]" value="rep-' . $reponses[0]->id . '" />';
                        echo '<textarea placeholder="Votre réponse..." name="fquest-' . $q->id . '[]" class="form-control" rows="5" id="comment"></textarea>';
                        echo '</div>';
                        
                    }
                    echo '
                            </div>
                        </div>';
                }

                echo '<button id="sendShotgunButton" idShotgun="'.$idShot.'" type="submit" class="btn btn-primary btn-lg btn-block">Envoyer</button>';


                /* Quand on clique sur le bouton, on renvoie tout à cette page avec $_POST['submitting'] = "true" de sorte qu'on sait
                 * qu'on est en train d'envoyer les données du formulaire et pas seulement en train de le remplir */
                echo '<input type="hidden" name="submitting" value="true" />';
                echo '</form>';
            }
        }
    }
    else
        redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Vous ne pouvez pas vous inscrire à ce shotgun !"));
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
    if(userMayUnsuscribe(DBi::$mysqli, $idShot, $_SESSION['isAdmin'], $_SESSION['mailUser']))
    {
        $shotgun = shotgun_event::shotgunGet(DBi::$mysqli, $idShot);

        if(inscription::doDesinscription(DBi::$mysqli, $idShot, $_SESSION['mailUser']))
            redirectWithPost('index.php?activePage=shotgunRecord&idShotgun=' . $shotgun->id, array('tip' => 'success', 'msg' => "Désinscription réussie !"));
        else
            redirectWithPost('index.php?activePage=index', array('tip' => 'error', 'msg' => "Impossible de se désinscrire !"));
}
    else
    {
        redirectWithPost('index.php?activePage=index' . $shotgun->id, array('tip' => 'error', 'msg' => "Vous ne pouvez pas vous désinscrire de ce shotgun !"));
        //header('Location: index.php?activePage=error&msg=Vous ne pouvez pas vous désinscrire de ce shotgun !');
    }
}















//echo '<script type="text/javascript">function myConfirmation(){return \'\';} window.onbeforeunload = myConfirmation;</script>';