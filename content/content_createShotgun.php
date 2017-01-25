<?php

require_once('shotgun_event.php');
require_once('DBi.php');

function doCreateShotgun($mysqli, $titre, $description, $mail_crea, $au_nom_de, $date_event, $date_publi, $nb_places, $prix, $anonymous, $link_thumbnail, $intitule, $typeReponse, $qcmrep)
{
    if (isset($titre) && $titre != "" &&
            isset($description) && $description != "" &&
            isset($mail_crea) && $mail_crea != "" &&
            isset($au_nom_de) && $au_nom_de != "" &&
            isset($date_event) && $date_event != "" &&
            isset($date_publi) && $date_publi != "" &&
            isset($anonymous) && ctype_digit($anonymous))
    {
        $idShotgun = shotgun_event::traiteShotgunForm($mysqli, $titre, $description, $date_event, $date_publi, $nb_places, $prix, $mail_crea, $au_nom_de, $anonymous, $link_thumbnail);
        $nQuest = count($intitule); // Nombre de questions
        for ($i = 0; $i < $nQuest; $i++)
        { // Traitons la question i 
            $idQuestion = question::traiteQuestionForm($mysqli, $intitule, $typeReponse, $idShotgun, $i); // Insertion de la question
            if ($typeReponse[$i] != question::$TYPE_REPONSELIBRE)
            {
                reponse::traiteChoixForm($mysqli, $idQuestion, $i, $qcmrep);
            } else
            {
                reponse::insertChoixLibre($mysqli, $idQuestion);
            }
        }
    }
}

if (isset($_GET["todoCreate"]) && $_GET["todoCreate"] == "createShotgun")
{
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $date_event = $_POST['date_event'];
    $date_publi = $_POST['date_publi'];
    $nb_places = $_POST['nb_places'];
    $prix = $_POST['prix'];
    $mail_crea = $_POST['mail_crea'];
    $au_nom_de = $_POST['au_nom_de'];
    $anonymous = $_POST['anonymous'];
    $link_thumbnail = $_POST['link_thumbnail'];
    $intitule = isset($_POST['intitule']) ? $_POST['intitule'] : array();
    $typeReponse = array();
    $qcmrep = array();
    $nQuest = count($intitule); // Nombre de questions
    for ($i = 0; $i < $nQuest; $i++)
    {
        $typeReponse[$i] = $_POST['typeReponse' . ($i + 1)];
        $nChoix = count($_POST['qcmrep' . ($i + 1)]); // Nombre de questions
        for ($j = 0; $j < $nChoix; $j++)
        {
            $qcmrep[$i][$j] = $_POST['qcmrep' . ($i + 1)][($j)];
        }
    }
    doCreateShotgun(DBi::$mysqli, $titre, $description, $mail_crea, $au_nom_de, $date_event, $date_publi, $nb_places, $prix, $anonymous, $link_thumbnail, $intitule, $typeReponse, $qcmrep);
} else
{
    echo<<<END
    <div class="fondColor">
    <br/>
    <form class="form-horizontal" action="index.php?activePage=createShotgun&todoCreate=createShotgun" method="post" >
        <div class="form-group ">
            <label for="inputTitle3" class="col-sm-2 control-label">Titre</label>
            <div class="col-sm-6">
                <input type="text" name ="titre" class="form-control" id="inputTitle3" placeholder="Titre du shotgun" required>
            </div>
        </div>
        <div class="form-group">
            <label for="inputDescription3" class="col-sm-2 control-label">Description</label>
            <div class="col-sm-6">
                <textarea class="form-control" name = "description" id="inputDescription3" placeholder="Description de l'évènement"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="inputMailCrea3"  class="col-sm-2 control-label">E-mail du responsable</label>
            <div class="col-sm-6">
                <input type="email" name = "mail_crea" class="form-control" id="inputMailCrea3" placeholder="E-mail" required>
            </div>
        </div>
        <div class="form-group">
            <label for="inputOrganisateur3" class="col-sm-2 control-label">Nom du groupe organisateur</label>
            <div class="col-sm-6">
                <input type="text" name="au_nom_de" class="form-control" id="inputOrganisateur3" placeholder="Ex : Binet Sud-Ouest" required>
            </div>
        </div>
        <div class="form-group">
            <label for="inputDate_event3" class="col-sm-2 control-label">Date et heure de début</label>
            <div class="col-sm-6">
                <input type="datetime" name="date_event" class="form-control" id="inputDate_event3" required>
            </div>
        </div>
        <div class="form-group">
            <label for="inputDate_shotgun3" class="col-sm-2 control-label">Date et heure de début de shotgun</label>
            <div class="col-sm-6">
                <input type="datetime" name="date_publi" class="form-control" id="inputDate_shotgun3" required>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-6">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="placeLimBool" onclick='$("#Nb_places").toggle();'> Nombre de places limitées
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group cache" id="Nb_places" >
            <label for="inputNb_places3" id ="labelplace" class="col-sm-2 control-label" >Nombre de places</label>
            <div class="col-sm-6">
                <input type="number" name="nb_places" value = 0 class="col-sm-2 control-label" id="inputNb_places3">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-6">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="payantBool" onclick='$("#PrixQ").toggle();'> Payant
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group cache" id="PrixQ">
            <label for="inputPrix3" id="labelprix" class="col-sm-2 control-label">Prix</label>
            <div class="col-sm-6">
                <input type="number" name="prix" value = 0 class="col-sm-2 control-label" id="inputPrix3">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">La liste des participants est-elle privée?</label>
            <div class="col-sm-6">
                <input type="radio" name="anonymous" value="1" required>   oui
                <br>
                <input type="radio"  name="anonymous" value="0">   non
            </div>
        </div>
        <div class="form-group">
            <label for="inputimage3" class="col-sm-2 control-label">Image illustrative</label>
            <div class="col-sm-6">
                <input type="file" name="link_thumbnail" class="form-control" id="inputimage3" >
            </div>
        </div>
        <div  class="form-group">
            <input type='button' id='ajouteQuestion' value='Ajouter une question' class='btn btn-default ajout_boutonQ col-sm-2 ' />
            <div class=" col-sm-6 input_fields_wrapQ" id="question">
                
            </div>
        </div>
        <div class="form-group">
            <div class=" col-sm-offset-4 col-lg-4">
                <button type="submit" class="btn btn-default">Lancer un nouveau shotgun</button>
            </div>
        </div>
    </form>
    </div>
END;
}