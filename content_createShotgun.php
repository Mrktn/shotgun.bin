<head>
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="js/shotgunForm.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/perso.css" rel="stylesheet">
</head>
<body>
<form class="form-horizontal" action="index.php?activePage=new_shotgun&todoShotgun=create_shotgun" method="post" >
  <div class="form-group">
    <label for="inputTitle3" class="col-sm-2 control-label">Titre</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="inputTitle3" placeholder="Titre du shotgun" required>
    </div>
  </div>
  <div class="form-group">
    <label for="inputDescription3" class="col-sm-2 control-label">Description</label>
    <div class="col-sm-10">
        <textarea class="form-control" id="inputDescription3" placeholder="Description de l'évènement"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="inputMailCrea3" class="col-sm-2 control-label">E-mail du responsable</label>
    <div class="col-sm-10">
        <input type="email" class="form-control" id="inputMailCrea3" placeholder="E-mail" required>
    </div>
  </div>
  <div class="form-group">
    <label for="inputOrganisateur3" class="col-sm-2 control-label">Nom du groupe organisateur</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="inputOrganisateur3" placeholder="Ex : Binet Sud-Ouest" required>
    </div>
  </div>
  <div class="form-group">
    <label for="inputDate_event3" class="col-sm-2 control-label">Date et heure de début</label>
    <div class="col-sm-10">
        <input type="datetime" class="form-control" id="inputDate_event3" required>
    </div>
  </div>
  <div class="form-group">
    <label for="inputDate_shotgun3" class="col-sm-2 control-label">Date et heure de début de shotgun</label>
    <div class="col-sm-10">
        <input type="datetime" class="form-control" id="inputDate_shotgun3" required>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" onclick='$("#Nb_places").toggle();'> Nombre de places limitées
        </label>
      </div>
    </div>
  </div>
  <div class="form-group cache" id="Nb_places" >
      <label for="inputNb_places3" id ="labelplace" class="col-sm-2 control-label" >Nombre de places</label>
      <div class="col-sm-10">
          <input type="number" class="col-sm-2 control-label" id="inputNb_places3">
      </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" onclick='$("#PrixQ").toggle();'> Payant
        </label>
      </div>
    </div>
  </div>
  <div class="form-group cache" id="PrixQ">
      <label for="inputPrix3" id="labelprix" class="col-sm-2 control-label">Prix</label>
      <div class="col-sm-10">
          <input type="number" class="col-sm-2 control-label" id="inputPrix3">
      </div>
  </div>
  <div class="form-group">
    <label for="anonymous" class="col-sm-2 control-label">La liste des participants est-elle privée?</label>
    <div class="col-sm-10">
        <input type="radio" name="anonymous" value="oui" required>   oui
        <br>
        <input type="radio"  name="anonymous" value="non">   non
    </div>
  </div>
  <div class="form-group">
    <label for="inputimage3" class="col-sm-2 control-label">Image illustrative</label>
    <div class="col-sm-10">
        <input type="file" class="form-control" id="inputimage3" name="image">
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