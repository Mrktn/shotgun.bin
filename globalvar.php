<?php


// Quel est le titre à afficher dans la barre de navigation ?
$title = array("index" => "shotgun.bin", "register" => "S'enregistrer", "error" => "Erreur", "info" => "Information", "displayShotguns" => "Shotguns ouverts", "manageShotguns" => "Gérer les shotguns", "shotgunRecord" => "Consulter un shotgun", "myShotguns" => "Mes shotguns créés", "shotgunIt" => "Inscription", "createShotgun" => "Créer un nouveau shotgun","myShotgunsReserves" => "Mes shotguns","changePassword" => "Changer son mot de passe");

// Quel est le nom de chaque page dans la navbar ?
$titleNavbar = array("index" => "Accueil", "register" => "S'enregistrer", "displayShotguns" => "Shotguns en cours", "placeholderloggedin" => "Réservé aux utilisateurs enregistrés", "manageShotguns" => "Administrer", "myShotguns" => "Administrer", "createShotgun" => "Poster un shotgun","myShotgunsReserves" => "Mes inscriptions");

// Quelles sont les pages dans la navbar quand je suis pas logged in ?
$navbarLoggedOut = array("index", "register");

// Quelles sont les pages dans la navbar quand je suis simple user ?
$navbarSimpleUser = array("index", "displayShotguns","myShotgunsReserves", "createShotgun", "myShotguns");

// Quelles sont les pages dans la navbar quand je suis admin ?
$navbarAdmin = array("index", "displayShotguns");

// Quelles sont les pages qui requièrent d'être admin pour les voir ?
$adminPages = array("manageShotguns");

// Quelles sont les pages que je suis autorisé à voir en étant logged out ?
$authorizedLoggedOut = array("index", "register", "error", "info");

// Quelles sont les pages que je suis autorisé à voir en étant logged in (admin ou user, peu importe) ?
$authorizedLoggedIn = array("index", "error", "placeholerloggedin", "info", "displayShotguns", "manageShotguns", "shotgunRecord", "myShotguns", "shotgunIt", "createShotgun","myShotgunsReserves","changePassword");

// $accessibleSimpleUser['une page d'admin'] = false
$accessibleSimpleUser = array("index" => true, "register" => true, "error" => true, "info" => true, "manageShotguns" => false, "shotgunRecord" => true, "myShotguns" => true, "shotgunIt" => true, "createShotgun" => true,"myShotgunsReserves" => true, "changePassword" => true);
