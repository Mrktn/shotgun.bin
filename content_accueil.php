<?php
if (!isset($_SESSION['mailUser']))
{ // Page d'accueil pour les non inscrits
} else
{ // Page d'accueil pour les inscrits
}
?>
<div class="container">
    <div class="row">
        <div class="col-sm-5 col-md-5 control-label"> Bloc à venir</div>
        <div class="col-sm-2 col-md-2 control-label"> Bloc de transition </div>
        <div class="col-sm-5 col-md-5 control-label"> Bloc mon agenda </div>
    </div>
</div>
<div class="container">
   <div class="row">
      <div class="col-md-2 col-sm-6 bleu">colonne bleue</div>
      <div class="col-md-4 col-sm-6 vert">colonne 
verte</div>
      <div class="col-md-6 col-sm-6 rouge">colonne 
rouge</div>
   </div>
</div>