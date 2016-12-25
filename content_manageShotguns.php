<?php

require('shotgun_event.php');

$shotgunsInactive = shotgun_event::getInactiveShotguns($mysqli);

// Les shotguns en cours tels que visibles par les utilisateurs
$shotgunsUpAndRunning = shotgun_event::getVisibleShotguns($mysqli);

// On récupère les shotguns dont la date de publi n'est pas encore arrivée, mais qui ont déjà été activés par l'admin
// (sinon il est déjà apparu dans les shotguns inactifs)
$shotgunsAVenir = shotgun_event::getActiveAVenirShotguns($mysqli);

echo '<div class="container">
  <div class="jumbotron center-block">
    <h2 style="align:center">Page d\'administration</h2>
  </div>';
echo '<div class="container">';
echo <<<END
<div id="menu">
    <div class="panel list-group">
        <a href="#" class="list-group-item" data-toggle="collapse" data-target="#sm" data-parent="#menu">
END;

if(count($shotgunsInactive) != 0)
{
    echo '<span class="label label-danger">' . count($shotgunsInactive) . '</span>';
}
else
{
    echo '<span class="label label-primary">' . count($shotgunsInactive) . '</span>';
}
echo <<<END
<strong>Shotguns non approuvés</strong>
END;



echo '<span class="glyphicon glyphicon-remove pull-right"></span></a>';
echo '<div id="sm" class="sublinks collapse" style="margin-bottom: 40px">';


if(count($shotgunsInactive) == 0)
{
    echo '<a class="list-group-item small">Aucun shotgun à approuver !</a>';
}
else
{
    foreach($shotgunsInactive as $s)
    {
        echo '<a style="cursor:pointer" href="index.php?activePage=shotgunRecord&idShotgun=' . $s->id .'" class="list-group-item small"><span class="glyphicon glyphicon-chevron-right"></span> <strong>[' . $s->au_nom_de . '] </strong>' . $s->titre . '</a>';
    }
}

echo '</div><a href="#" class="list-group-item" data-toggle="collapse" data-target="#sl" data-parent="#menu">';

if(count($shotgunsUpAndRunning) != 0)
{
    echo '<span class="label label-success">' . count($shotgunsUpAndRunning) . '</span>';
}
else
{
    echo '<span class="label label-primary">' . count($shotgunsUpAndRunning) . '</span>';
}

echo '<strong>Tous les shotguns en cours</strong><span class="glyphicon glyphicon-list pull-right"></span></a>
        <div style="margin-bottom: 40px" id="sl" class="sublinks collapse">';

if(count($shotgunsUpAndRunning) == 0)
{
    echo '<a class="list-group-item small">Aucun shotgun en cours !</a>';
}
else
{
    foreach($shotgunsUpAndRunning as $s)
    {
        echo '<a style="cursor:pointer" href="index.php?activePage=shotgunRecord&idShotgun=' . $s->id .'" class="list-group-item small"><span class="glyphicon glyphicon-chevron-right"></span> <strong>[' . utf8_encode($s->au_nom_de) . '] </strong>' . utf8_encode($s->titre) . '</a>';
    }
}


echo '</div><a href="#" class="list-group-item" data-toggle="collapse" data-target="#s2" data-parent="#menu">';

if(count($shotgunsAVenir) != 0)
{
    echo '<span class="label label-success">' . count($shotgunsAVenir) . '</span>';
}
else
{
    echo '<span class="label label-primary">' . count($shotgunsAVenir) . '</span>';
}

echo '<strong>Shotguns à venir</strong><span class="glyphicon glyphicon-send pull-right"></span></a>
        <div style="margin-bottom: 40px" id="s2" class="sublinks collapse">';




if(count($shotgunsAVenir) == 0)
{
    echo '<a class="list-group-item small">Aucun shotgun à venir !</a>';
}
else
{
    foreach($shotgunsAVenir as $s)
    {
        echo '<a style="cursor:pointer" href="index.php?activePage=shotgunRecord&idShotgun=' . $s->id .'" class="list-group-item small"><span class="glyphicon glyphicon-chevron-right"></span> <strong>[' . utf8_encode($s->au_nom_de) . '] </strong>' . utf8_encode($s->titre) . ' (sera publié le : ' . strftime("%d %B %Y à %Hh%Mm%Ss)", strtotime($s->date_publi)). '</a>';
    }
}

echo '</div></div></div></div></div>';

