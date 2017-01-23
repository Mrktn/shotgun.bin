<?php
print_r($_POST);
print_r($_GET);
?>
<head>
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="js/shotgunForm.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/perso.css" rel="stylesheet">
</head>
<body>
    <form class="form-horizontal" action="content_createShotgun.php?activePage=new_shotgun&todoShotgun=create_shotgun" method="post" >
        <div class="form-group">
            <label for="inputTitle3" class="col-sm-2 control-label">Titre</label>
            <div class="col-sm-10">
                <input type="text" name ="titre" class="form-control" id="inputTitle3" placeholder="Titre du shotgun" required>
            </div>
        </div>
        <div class="form-group">
            <label for="inputDescription3" class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10">
                <textarea class="form-control" name = "description" id="inputDescription3" placeholder="Description de l'évènement"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="inputMailCrea3"  class="col-sm-2 control-label">E-mail du responsable</label>
            <div class="col-sm-10">
                <input type="email" name = "mail_crea" class="form-control" id="inputMailCrea3" placeholder="E-mail" required>
            </div>
        </div>
        <div class="form-group">
            <label for="inputOrganisateur3" class="col-sm-2 control-label">Nom du groupe organisateur</label>
            <div class="col-sm-10">
                <input type="text" name="au_nom_de" class="form-control" id="inputOrganisateur3" placeholder="Ex : Binet Sud-Ouest" required>
            </div>
        </div>
        <div class="form-group">
            <label for="inputDate_event3" class="col-sm-2 control-label">Date et heure de début</label>
            <div class="col-sm-10">
                <input type="datetime" name="date_event" class="form-control" id="inputDate_event3" required>
            </div>
        </div>
        <div class="form-group">
            <label for="inputDate_shotgun3" class="col-sm-2 control-label">Date et heure de début de shotgun</label>
            <div class="col-sm-10">
                <input type="datetime" name="date_publi" class="form-control" id="inputDate_shotgun3" required>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="placeLimBool" onclick='$("#Nb_places").toggle();'> Nombre de places limitées
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group cache" id="Nb_places" >
            <label for="inputNb_places3" id ="labelplace" class="col-sm-2 control-label" >Nombre de places</label>
            <div class="col-sm-10">
                <input type="number" name="nb_places" value = -1 class="col-sm-2 control-label" id="inputNb_places3">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="payantBool" onclick='$("#PrixQ").toggle();'> Payant
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group cache" id="PrixQ">
            <label for="inputPrix3" id="labelprix" class="col-sm-2 control-label">Prix</label>
            <div class="col-sm-10">
                <input type="number" name="prix" value = -1 class="col-sm-2 control-label" id="inputPrix3">
            </div>
        </div>
        <div class="form-group">
            <label for="anonymous" class="col-sm-2 control-label">La liste des participants est-elle privée?</label>
            <div class="col-sm-10">
                <input type="radio" name="anonymous" value="1" required>   oui
                <br>
                <input type="radio"  name="anonymous" value="0">   non
            </div>
        </div>
        <div class="form-group">
            <label for="inputimage3" class="col-sm-2 control-label">Image illustrative</label>
            <div class="col-sm-10">
                <input type="file" name="link_thumbnail" class="form-control" id="inputimage3" >
            </div>
        </div>
        <div  class="form-group">
            <div class="col-sm-offset-2 col-lg-10 input_fields_wrapQ" id="question">
                <input type='button' id='ajouteQuestion' value='Ajouter une question' class='btn btn-default ajout_boutonQ ' />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-lg-10">
                <button type="submit" class="btn btn-default">Lancer un nouveau shotgun</button>
            </div>
        </div>
    </form>
</body>

<!-- Pour la prochaine fois: traiter le POST avec BDD pour insérer le shtogun POST a pour champ titre description date_event date_publi placeLimBool nb_places payantBool prix anonymous (valant 1 si oui ou 0 sinon) link_thumbnail à mettre sous url
intitule[] contenant un tableau avec les intitulés de chaque question (Virer le n  dans shotgunForm)
typeReponseN contenant le type de reponse à la qeustion N
qcmrepN[] contenant l'ensemble des choix pour la question N
surement à implementer le placeLimBool... faire html special chars  + ??? dans le traitement
Pb deux fois Q4!!! Il faut compléter la fonction suppr pour que en cas de suppression les numéros de choix s'actualisent!
Idée : En partant de la racine pour chaque div id = blabla + parsing()-1