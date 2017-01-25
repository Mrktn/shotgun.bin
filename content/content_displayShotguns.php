<?php

echo '<div class="container center-block" style="width:100%; background-color: #ffffff">';
echo "<h1>Shotguns ouverts</h1>";
echo '<div class="container center-block" style="padding:15px">';

// On récupère les shotguns qui sont visibles, aka ouverts et actifs et non périmés
displayShotgunList(DBi::$mysqli, shotgun_event::getVisibleShotguns(DBi::$mysqli, $_SESSION['mailUser']));

echo '</div></div>';
?>