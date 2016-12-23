<?php

// Quel est le titre à afficher dans la barre de navigation ?
$title = array("index" => "shotgun.bin", "register" => "S'enregistrer", "error" => "Erreur");

// Quel est le nom de chaque page dans la navbar ?
$titleNavbar = array("index" => "Accueil", "register" => "S'enregistrer", "placeholderloggedin" => "Réservé aux utilisateurs enregistrés");
//

// Quelles sont les pages dans la navbar quand je suis pas logged in ?
$pagesLoggedOut = array("index", "register");

$pagesLoggedIn = array("index", "placeholderloggedin");

// $accessibleSimpleUser['une page d'admin'] = false
$accessibleSimpleUser = array("index" => true, "register" => true, "error" => true);
