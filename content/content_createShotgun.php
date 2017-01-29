<?php
require_once('classes/shotgun_event.php');
require_once('classes/DBi.php');
if(!isset($_SESSION['loggedIn']))
    redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Connectez-vous avant de créer un shotgun !"));

function doCreateShotgun($mysqli, $titre, $description, $au_nom_de, $date_event, $date_publi, $nb_places, $prix, $anonymous, $link_thumbnail, $intitule, $typeReponse, $qcmrep)
{
    // Si ce qu'on avait requis dans le formulaire est satisfait, on va travailler
    if(isset($titre) && !stringIsBlank($titre) &&
            isset($au_nom_de) && !stringIsBlank($au_nom_de) &&
            isset($date_event) && !stringIsBlank($date_event) &&
            isset($anonymous) && ctype_digit($anonymous) &&
            is_numeric($prix) &&
            is_numeric($nb_places) && ctype_digit($nb_places))
    {
        // Sera set à true dès que l'on aura eu un problème, ce qui permettra d'arrêter l'exécution des requêtes
        $failedFlag = false;

        $description = isset($description) ? $description : "";

        // Si ce n'est pas un lien valide, hors de question qu'on l'ajoute à la DB
        $link_thumbnail = (isset($link_thumbnail) && isLinkToPicture($link_thumbnail)) ? $link_thumbnail : "";

        // Conversion en un format compréhensible par strtotime avant de l'envoyer à date
        $date_publi = (!isset($date_publi) || $date_publi == "") ? date("Y-m-d H:i:s") : date("Y-m-d H:i:s", strtotime(preg_replace('/\//', '-', $date_publi)));
        $date_event = preg_replace('/\//', '-', $date_event);
        $date_event = date("Y-m-d H:i:s", strtotime($date_event));

        // On crée notre shotgun
        $idShotgun = shotgun_event::traiteShotgunForm($mysqli, $titre, $description, $date_event, $date_publi, intval($nb_places), floatval($prix), $_SESSION['mailUser'], $au_nom_de, $anonymous, $link_thumbnail);

        if($idShotgun == null)
            $failedFlag = true;

        // Nombre de questions
        $nQuest = count($intitule);

        $questionNegligeeExists = false;

        // On traite la question i
        for($i = 0; ($i < $nQuest) && !$failedFlag; $i++)
        {
            // Vérifions tout d'abord qu'en cas de réponse de type QCM l'utilisateur a bien rentré au moins un choix
            $choixValideExists = ($typeReponse[$i] == question::$TYPE_REPONSELIBRE);

            // Nombre de choix pour la question nQuestion
            $nChoix = count($qcmrep[$i]) - 1;

            // La variable sera à true à la fin ssi il existe au moins un choix non vide
            while(!($choixValideExists) && ($nChoix >= 0))
            {
                $choixValideExists = $choixValideExists || !stringIsBlank($qcmrep[$i][$nChoix]);
                $nChoix = $nChoix - 1;
            }

            if($choixValideExists)
            {
                // Vérifions que la question a un format correct.
                if(!stringIsBlank($intitule[$i]) && ($typeReponse[$i] == '0' || $typeReponse[$i] == '1' || $typeReponse[$i] == '2'))
                {
                    $idQuestion = question::traiteQuestionForm($mysqli, $intitule, $typeReponse, $idShotgun, $i); // Insertion de la question

                    $failedFlag = $failedFlag || ($idQuestion == null);

                    if(!$failedFlag && $typeReponse[$i] != question::$TYPE_REPONSELIBRE)
                        $failedFlag = $failedFlag || !reponse::traiteChoixForm($mysqli, $idQuestion, $i, $qcmrep);
                    else
                        $failedFlag = $failedFlag || !reponse::insertChoixLibre($mysqli, $idQuestion);
                }
                
                else
                    $questionNegligeeExists = true;
            }

            else
                $questionNegligeeExists = true;
        }

        if($failedFlag)
            redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Erreur innatendue, contactez un administrateur."));
        elseif($questionNegligeeExists)
            redirectWithPost("index.php?activePage=shotgunRecord&idShotgun=$idShotgun", array('tip' => 'warning', 'msg' => "Shotgun créé avec succès ! <b>En revanche, certaines de vos questions n'ont pas pu être traitées. </b><br/>Lorsque la date de publication sera passée, votre shotgun sera visible des utilisateurs à condition que l'administrateur l'ait <b>autorisé</b> !"));
        else
            redirectWithPost("index.php?activePage=shotgunRecord&idShotgun=$idShotgun", array('tip' => 'success', 'msg' => "Shotgun créé avec succès !<br/>Lorsque la date de publication sera passée, votre shotgun sera visible des utilisateurs à condition que l'administrateur l'ait <b>autorisé</b> !"));
        
        
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
    $nb_places = (!isset($_POST["placeLimBool"]) || !$_POST["placeLimBool"]) ? "0" : $_POST["nb_places"];
    $prix = (!isset($_POST["payantBool"]) || !$_POST["payantBool"]) ? "0" : $_POST["prix"];
    $au_nom_de = $_POST['au_nom_de'];
    $anonymous = $_POST['anonymous'];
    $link_thumbnail = $_POST['link_thumbnail'];
    $intitule = isset($_POST['intitule']) ? $_POST['intitule'] : array();
    $typeReponse = array();
    $qcmrep = array();
    $nQuest = count($intitule); // Nombre de questions

    for($i = 0; $i < $nQuest; $i++)
    {
        $typeReponse[$i] = isset($_POST['typeReponse' . ($i + 1)]) ? $_POST['typeReponse' . ($i + 1)] : '-1';
        $nChoix = count($_POST['qcmrep' . ($i + 1)]); // Nombre de questions
        for($j = 0; $j < $nChoix; $j++)
            $qcmrep[$i][$j] = isset($_POST['qcmrep' . ($i + 1)][($j)]) ? $_POST['qcmrep' . ($i + 1)][($j)] : '';
    }

    doCreateShotgun(DBi::$mysqli, $titre, $description, $au_nom_de, $date_event, $date_publi, $nb_places, $prix, $anonymous, $link_thumbnail, $intitule, $typeReponse, $qcmrep);
}
else
{
    ?>
    <div class ='container-fluid titlepage' > <h1>Formulaire de création</h1></div><br/><br/>

    <div class="container">
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
                    <input id="inputDate_event3" type='text' name ='date_event' class="form-control" /><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> 
                </div>
            </div>

            <div class="form-group">
                <label for="inputDate_shotgun3" class="col-sm-2 control-label">Ouverture du shotgun</label>
                <div class='col-sm-6 date input-group'>
                    <input id="inputDate_shotgun3" type='text' class="form-control" name ='date_publi' placeholder="Vide pour une ouverture immédiate (modulo approbation de l'administrateur)"/><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> 
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="placeLimBool" value="true" onclick='$("#Nb_places").toggle();'><strong>Nombre de places limitées ?</strong>
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
                            <input type="checkbox" name="payantBool" value = "true" onclick='$("#PrixQ").toggle();'> <strong>Payant ?</strong>
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
                <div style="width:80%" class="center-block input_fields_wrapQ" id="question">

                </div>
                <input type='button' id='ajouteQuestion' value='Ajouter une question' class='btn btn-default ajout_boutonQ' />

            </div>
            <div class="text-center form-group">
                <button type="submit" class="btn btn-danger">Lancer un nouveau shotgun !</button>
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
    <?php
}
?>