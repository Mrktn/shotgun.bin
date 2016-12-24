<?php


// Quel est le titre à afficher dans la barre de navigation ?
$title = array("index" => "shotgun.bin", "register" => "S'enregistrer", "error" => "Erreur", "info" => "Information", "displayShotguns" => "Shotguns ouverts", "manageShotguns" => "Gérer les shotguns");

// Quel est le nom de chaque page dans la navbar ?
$titleNavbar = array("index" => "Accueil", "register" => "S'enregistrer", "displayShotguns" => "Shotguns ouverts", "placeholderloggedin" => "Réservé aux utilisateurs enregistrés", "manageShotguns" => "Administrer");
//

// Quelles sont les pages dans la navbar quand je suis pas logged in ?
$navbarLoggedOut = array("index", "register");

// Quelles sont les pages qui requièrent d'être admin pour les voir ?
$adminPages = array("manageShotguns");

// Quelles sont les pages dans la navbar quand je suis logged in ?
$navbarLoggedIn = array("index", "displayShotguns");

// Quelles sont les pages dans la navbar quand je suis logged in en tant qu'admin ?
$navbarLoggedIn = array("index", "displayShotguns", "manageShotguns");

// Quelles sont les pages que je suis autorisé à voir en étant logged out ?
$authorizedLoggedOut = array("index", "register", "error", "info");

// Quelles sont les pages que je suis autorisé à voir en étant logged in ?
$authorizedLoggedIn = array("index", "error", "placeholerloggedin", "info", "displayShotguns", "manageShotguns");

// $accessibleSimpleUser['une page d'admin'] = false
$accessibleSimpleUser = array("index" => true, "register" => true, "error" => true, "info" => true);
