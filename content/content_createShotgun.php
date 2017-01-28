<?php

require_once('classes/shotgun_event.php');
require_once('classes/DBi.php');

if(!isset($_SESSION['loggedIn']))
    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Connectez-vous avant de créer un shotgun !"));

function doCreateShotgun($mysqli, $titre, $description, $au_nom_de, $date_event, $date_publi, $nb_places, $prix, $anonymous, $link_thumbnail, $intitule, $typeReponse, $qcmrep)
{
    if(isset($titre) && $titre != "" &&
            isset($au_nom_de) && $au_nom_de != "" &&
            isset($date_event) && $date_event != "" &&
            isset($anonymous) && ctype_digit($anonymous) &&
            is_numeric($prix) &&
            is_numeric($nb_places) && ctype_digit($nb_places))
    {
        $failedFlag = false;

        $description = isset($description) ? $description : "";
        $link_thumbnail = isset($link_thumbnail) ? $link_thumbnail : "";
        $date_publi = (!isset($date_publi) || $date_publi == "") ? date("Y-m-d H:i:s") : $date_publi;
        
        $date_event = preg_replace('/\//', '-', $date_event);
        $date_event = date("Y-m-d H:i:s", strtotime($date_event));
        
        $idShotgun = shotgun_event::traiteShotgunForm($mysqli, $titre, $description, $date_event, $date_publi, intval($nb_places), floatval($prix), $_SESSION['mailUser'], $au_nom_de, $anonymous, $link_thumbnail);

        if($idShotgun == null)
            $failedFlag = true;

        $nQuest = count($intitule); // Nombre de questions

        for($i = 0; ($i < $nQuest) && !$failedFlag; $i++)
        {
            $idQuestion = question::traiteQuestionForm($mysqli, $intitule, $typeReponse, $idShotgun, $i); // Insertion de la question

            $failedFlag = $failedFlag || ($idQuestion == null);

            if($failedFlag)
                echo '<b>ça a raté !!</b>';

            if(!$failedFlag && $typeReponse[$i] != question::$TYPE_REPONSELIBRE)
                 $failedFlag = $failedFlag || !reponse::traiteChoixForm($mysqli, $idQuestion, $i, $qcmrep);
            else
                $failedFlag = $failedFlag || !reponse::insertChoixLibre($mysqli, $idQuestion);
        }

        if($failedFlag)
            redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Erreur innatendue, contactez un administrateur."));
    }
    
    else
        redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Formulaire invalide !"));
}

if(isset($_GET["todoCreate"]) && $_GET["todoCreate"] == "createShotgun")
{
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $date_event = $_POST['date_event'];
    $date_publi = $_POST['date_publi'];
    $nb_places = $_POST['nb_places'];
    $prix = $_POST['prix'];
    $au_nom_de = $_POST['au_nom_de'];
    $anonymous = $_POST['anonymous'];
    $link_thumbnail = $_POST['link_thumbnail'];
    $intitule = isset($_POST['intitule']) ? $_POST['intitule'] : array();
    $typeReponse = array();
    $qcmrep = array();
    $nQuest = count($intitule); // Nombre de questions

    for($i = 0; $i < $nQuest; $i++)
    {
        $typeReponse[$i] = $_POST['typeReponse' . ($i + 1)];
        $nChoix = count($_POST['qcmrep' . ($i + 1)]); // Nombre de questions
        for($j = 0; $j < $nChoix; $j++)
            $qcmrep[$i][$j] = $_POST['qcmrep' . ($i + 1)][($j)];
    }

    doCreateShotgun(DBi::$mysqli, $titre, $description, $au_nom_de, $date_event, $date_publi, $nb_places, $prix, $anonymous, $link_thumbnail, $intitule, $typeReponse, $qcmrep);
}
else
{
    echo "<div class ='container-fluid titlepage' > <h1>Formulaire de création</h1>
 </div>";
    echo<<<END
    <div class="container">
    <br/>
    <h2>Informations générales</h2><br/>
    <form data-toggle="validator" class="form-horizontal" action="index.php?activePage=createShotgun&todoCreate=createShotgun" method="post" >
        <div class="form-group">
            <label for="inputTitle3" class="col-sm-2 control-label">Titre</label>
            <div class="col-sm-6">
                <input name ="titre" class="form-control" id="inputTitle3" data-error="Entrez un titre non vide" placeholder="Titre du shotgun (requis)" required>
            </div>
        </div>
        <div class="form-group">
            <label for="inputDescription3" class="col-sm-2 control-label">Description</label>
            <div class="col-sm-6">
                <textarea class="form-control" name="description" id="inputDescription3" placeholder="Description de l'évènement"></textarea>
            </div>
        </div>
    
    <div class="form-group">
            <label for="inputOrganisateur3" class="col-sm-2 control-label">Responsable</label>
            <div class="col-sm-6">
                <input type="text" name="au_nom_de" class="form-control" id="inputOrganisateur3" placeholder="Binet &#x0153;no, moi, mon cousin, ... (requis)" required>
            </div>
        </div>
         
            <div class="form-group">
                <label for="inputDate_event3" class="col-sm-2 control-label">Date de l'évènement</label>
                <div class="col-sm-6 date input-group">
                    <input id="inputDate_event3" type='datetime' name ='date_event' required="true" class="form-control" /><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> 
                </div>
            </div>
    
    <div class="form-group">
                <label for="inputDate_shotgun3" class="col-sm-2 control-label">Ouverture du shotgun</label>
                <div class='col-sm-6 date input-group'>
                    <input id="inputDate_shotgun3" type='datetime' class="form-control" name ='date_publi' placeholder="Vide pour une ouverture immédiate (modulo approbation de l'administrateur)"/><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> 
                </div>
            </div>
    
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-6">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="placeLimBool" onclick='$("#Nb_places").toggle();'><strong>Nombre de places limitées ?</strong>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group cache" id="Nb_places" >
            <label for="inputNb_places3" id ="labelplace" class="col-sm-2 control-label" >Nombre de places</label>
            <div class="col-sm-6">
                <input type="number" min=0 name="nb_places" value=0 class="col-sm-2 control-label" id="inputNb_places3">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-6">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="payantBool" onclick='$("#PrixQ").toggle();'> <strong>Payant ?</strong>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group cache" id="PrixQ">
            <label for="inputPrix3" id="labelprix" class="col-sm-2 control-label">Prix (€)</label>
            <div class="col-sm-6">
                <input type="number" step="0.1" name="prix" value=0 class="col-sm-2 control-label" id="inputPrix3">
            </div>
        </div>
        <div class="form-group">
            	 <label class="col-sm-2 control-label">La liste des participants est-elle privée ? <span title="Autorisez-vous un utilisateur à voir la liste des gens déjà inscrits au moment de son inscription ?" class="glyphicon glyphicon-info-sign"></span></label>
            <div class="col-sm-6">
                <input type="radio" name="anonymous" value="1" required>   Oui
                <input type="radio"  name="anonymous" value="0" required>   Non
            </div>
        </div>
        <div class="form-group">
            <label for="inputimage3" class="col-sm-2 control-label">Image illustrative</label>
            <div class="col-sm-6">
                <input placeholder="Lien vers une image externe" type="url" name="link_thumbnail" class="form-control" id="inputimage3" >
            </div>
        </div>
    
    <br/><h2>Ajouter des questions</h2><br/>
    
        <div  class="form-group">
            <input type='button' id='ajouteQuestion' value='Ajouter une question' class='btn btn-default ajout_boutonQ col-sm-2 ' />
            <div class=" col-sm-6 input_fields_wrapQ" id="question">
                
            </div>
        </div>
        <div class="form-group">
            <div style="float:right">
                <button type="submit" class="btn btn-danger">Lancer un nouveau shotgun !</button>
            </div>
        </div>
    </form>
    </div>
    
    <script type="text/javascript">
            $(function () {
                $('#inputDate_event3').datetimepicker({
                    locale: 'fr'
                });
            });
    
            $(function () {
                $('#inputDate_shotgun3').datetimepicker({
                    locale: 'fr'
                });
            });
    
    
        </script>
END;
}
