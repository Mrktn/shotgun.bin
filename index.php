<?php

session_name("thesess"); // Session : pour la persistance : cookies qui perdure savoir si on est co ou pas
// ne pas mettre d'espace dans le nom de session !
session_start();
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}
// Décommenter la ligne suivante pour afficher le tableau $_SESSION pour le debuggage
//print_r($_SESSION);
?>
<?php
require('database.php');
$dbh = Database::connect();
require('logInOut.php');
require('utils.php');
require('printForm.php');
?>

<?php

// traitement des contenus de formulaires
    //on regarde s'il y a quelque chose à faire 'todo' , si oui on regarde si c'est un login ou un loggout et on execute le cas échéant
if (isset($_GET['todo']) && ($_GET['todo'] == 'login')) {
    //tentative de connexion , on a alors accès à ce qui a été entré via POST
    logIn($dbh);
}
if (isset($_GET['todo']) && $_GET['todo'] == 'logout') {
    //tentative de déconnexion
    logOut();
}
?>

<?php
generateHTMLHeader("shotgun.bin");
generateNavBar("index.php");
//generateMenu("index.php"); je te le commente pour test mon truc generate navbar
?>

<?php
// Mettre ici les pages à ouvrir en gérant la sécurité cf TD suivant le statut connecté/déconnecté/admin
?>

<?php
// Affichons ici la navbar suivant que l'utilisateur est connecté ou non

?>

<?php

// Si l'utilisateur tente le login admin
if (isset($_GET['error']))
    echo "<div class=\"container-fluid\" style=\"background-color: #f1545b; margin: 70px\">{$_GET["error"]}</div>";
if (isset($_POST['logAdmin']))
    redirectError("Pas encore implémenté le login admin !");
if (isset($_POST['logFrankiz']))
    redirectError("Pas encore implémenté le login Frankiz !");
if (isset($_GET['validateLogin']))
    echo "Aïe Aïe, logged in via Frankiz !!";
//do_login();
echo'<div class="container-fluid" style="background-color: #f7ecb5; margin: 70px">Hello, today is ' . date("l") . '</div>';

generateHTMLFooter();
?>
