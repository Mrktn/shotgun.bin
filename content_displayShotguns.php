<?php

echo '<div class="container center-block" style="width:100%; background-color: #ffffff">';
echo "<h1>Shotgun ouverts</h1>";
echo '<div class="container center-block" style="padding:15px">';

// On récupère les shotguns qui sont visibles, aka ouverts et actifs et non périmés
displayShotgunList($mysqli, shotgun_event::getVisibleShotgunsNotMine($mysqli, $_SESSION['mailUser']));


echo '</div></div>';
?>