<?php


if(!isset($_GET['idShotgun']) || !shotgun_event::shotgunIsInDB($mysqli, $_GET['idShotgun']))
    header('Location: index.php?activePage=error&msg=Impossible d\'afficher ce shotgun !');

// À ce stade on sait que le shotgun est dans la database.

$id = $_GET['idShotgun'];

if(!shotgun_event::shotgunIsVisible($mysqli, $id))
    header('Location: index.php?activePage=error&msg=Vous n\'avez pas les permissions pour voir ce shotgun !');

// À ce stade on sait que l'utilisateur peut consulter le shotgun.

echo <<<END
<div class="card text-xs-center">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs float-xs-left">
      <li class="nav-item">
        <a class="nav-link active" href="#">Active</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li>
    </ul>
  </div>
  <div class="card-block">
    <h4 class="card-title">Special title treatment</h4>
    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
    <a href="#" class="btn btn-primary">Go somewhere</a>
  </div>
</div>

END;



?>