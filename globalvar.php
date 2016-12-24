<?php


// Quel est le titre à afficher dans la barre de navigation ?
$title = array("index" => "shotgun.bin", "register" => "S'enregistrer", "error" => "Erreur", "info" => "Information", "displayShotguns" => "Shotguns ouverts");

// Quel est le nom de chaque page dans la navbar ?
$titleNavbar = array("index" => "Accueil", "register" => "S'enregistrer", "displayShotguns" => "Shotguns ouverts", "placeholderloggedin" => "Réservé aux utilisateurs enregistrés");
//

// Quelles sont les pages dans la navbar quand je suis pas logged in ?
$navbarLoggedOut = array("index", "register");

// Quelles sont les pages dans la navbar quand je suis logged in ?
$navbarLoggedIn = array("index", "displayShotguns");

// Quelles sont les pages que je suis autorisé à voir en étant logged out ?
$authorizedLoggedOut = array("index", "register", "error", "info");

// Quelles sont les pages que je suis autorisé à voir en étant logged in ?
$authorizedLoggedIn = array("index", "error", "placeholerloggedin", "info", "displayShotguns");

// $accessibleSimpleUser['une page d'admin'] = false
$accessibleSimpleUser = array("index" => true, "register" => true, "error" => true, "info" => true);
