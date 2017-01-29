<?php

session_name("thesess"); // Session : pour la persistance : cookies qui perdure savoir si on est co ou pas
// ne pas mettre d'espace dans le nom de session !
session_start();

setlocale(LC_ALL, 'fr_FR.utf8', 'fra');
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

if(!isset($_SESSION['initiated']))
{
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}

require('classes/DBi.php');
DBi::connect();

require('logInOut.php');
require('printForm.php');
require('globalvar.php');
require('utils.php');
require('classes/shotgun_event.php');
require('classes/inscription.php');
require('classes/question.php');
require('classes/reponse.php');
require_once('classes/reponse_de_utilisateur.php');

//traitement des contenus de formulaires
//on regarde s'il y a quelque chose à faire 'todo' , si oui on regarde si c'est un login ou un loggout et on execute le cas échéant
if(isset($_GET['todo']) && ($_GET['todo'] == 'login'))
{
    //tentative de connexion , on a alors accès à ce qui a été entré via POST
    logIn(DBi::$mysqli);
}

if(isset($_GET['todo']) && $_GET['todo'] == 'logout')
{
    //tentative de déconnexion
    logOut();
    session_destroy();
}

// Si on a du boulot à faire du point de vue des shotguns (fermer, ouvrir, activer, désactiver, supprimer)
if(isset($_GET['todoShotgunIt']))
{
    $action = $_GET['todoShotgunIt'];

    if(!isset($_GET['idShotgun']) || !isset($_SESSION['loggedIn']))
        redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => 'Requête mal formée !'), true);

    elseif($action == "closeShotgun" || $action == 'openShotgun')
    {
        if(shotgun_event::userMayCloseOpenShotgun(DBi::$mysqli, $_GET['idShotgun'], $_SESSION['mailUser'], $_SESSION['isAdmin']))
        {
            if(shotgun_event::updateShotgun(DBi::$mysqli, $_GET['idShotgun'], $action))
            {
                $_POST['tip'] = 'success';
                $_POST['msg'] = ($action == 'openShotgun' ? "Le shotgun a bien été ouvert !" : "Vous avez bien fermé le shotgun !");
            }
            else
            {
                $_POST['tip'] = 'error';
                $_POST['msg'] = "Impossible d'effectuer cette action... Contactez l'administrateur.";
            }
        }
        else
            redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => 'Vous n\'avez pas les droits nécessaires pour ouvrir/fermer ce shotgun !'), true);
    }

    // Seuls les admins peuvent delete
    elseif($action == 'deleteShotgun')
    {
        if(shotgun_event::userMayDeleteShotgun(DBi::$mysqli, $_GET['idShotgun'], $_SESSION['mailUser'], $_SESSION['isAdmin']))
        {
            if(shotgun_event::updateShotgun(DBi::$mysqli, $_GET['idShotgun'], 'deleteShotgun'))
                redirectWithPost("index.php?activePage=index", array('tip' => 'success', 'msg' => 'Le shotgun a bien été supprimé.'), true);
            else
                redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => 'Impossible de supprimer le shotgun... Contactez un administrateur.'), true);
        }
        else
            redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => 'Vous n\'avez pas les permissions nécessaires pour supprimer ce shotgun !'), true);
    }

    // Il faut être admin pour activer / désactiver (autoriser / interdire)
    elseif($action == 'activateShotgun' || $action == 'disableShotgun')
    {
        if(shotgun_event::userMayActivateDisableShotgun(DBi::$mysqli, $_GET['idShotgun'], $_SESSION['mailUser'], $_SESSION['isAdmin']))
        {
            if(shotgun_event::updateShotgun(DBi::$mysqli, $_GET['idShotgun'], $action))
                redirectWithPost("index.php?activePage=manageShotguns", array('tip' => 'success', 'msg' => ($action == 'activateShotgun' ? "Vous avez autorisé un shotgun !" : "Le shotgun a bien été désactivé !")), true);
            else
                redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Erreur inconnue, contactez un administrateur."), true);
        }
        else
            redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => 'Il faut être administrateur pour activer / désactiver un shotgun !'), true);
    }

    elseif($action == 'suscribe' || $action == 'unsuscribe')
    {
        
    }
    else
        redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => 'Action invalide !'), true);
}

// FIXME: clean this mess
elseif(isset($_GET['todoCreate']))
{
    if($_GET['todoCreate'] == "createShotgun")
    {
        
    }
    else
    {
        echo "Valeur interdite pour cette variable : {$_GET['todoCreate']}";
    }
}

// Si on a un truc dans l'URL qui dit ce qu'on doit afficher, on le fait
if(isset($_GET['activePage']))
{
    // Si ça n'est pas une clé du tableau title, c'est que ce n'est pas une page qui existe (l'utilisateur a pu mettre activePage=blblbl),
    // on va simplement le renvoyer sur l'index pour le punir
    if(!array_key_exists($_GET['activePage'], $title))
    {
        redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => 'Page invalide !'), true);
    }

    // Sinon, c'est une page qui existe
    else
    {
        // C'est là qu'on gère les erreurs
        if($_GET['activePage'] == 'error')
        {
            generateHTMLHeader("Erreur");
            generateNavBar($_GET['activePage'], isset($_SESSION['loggedIn']));

            require('content/content_error.php');
        }

        // Ici on gère les informations à l'utilisateur
        else if($_GET['activePage'] == 'info')
        {
            generateHTMLHeader("Information");
            generateNavBar($_GET['activePage'], isset($_SESSION['loggedIn']));

            require('content/content_info.php');
        }
        //echo "La clé existe <br/>";
        // Si l'utilisateur est logué et la page est accessible aux utilisateur logués
        // ou si l'utilisateur n'est pas logué et la page est accessible aux utilisateur non logués
        // Alors on le renvoie effectivement sur cette page
        else if((isset($_SESSION['loggedIn']) && in_array($_GET['activePage'], $authorizedLoggedIn)) || (!isset($_SESSION['loggedIn']) && in_array($_GET['activePage'], $authorizedLoggedOut)))
        {
            // On rajoute la condition que si c'est une page d'admin, il faut que l'utilisateur soit admin
            if(!in_array($_GET['activePage'], $adminPages) || (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']))
            {
                // Les rageux diront que c'est pas sécurisé. Les vrais sauront.
                generateHTMLHeader($title[$_GET['activePage']]);
                generateNavBar($_GET['activePage'], isset($_SESSION['loggedIn']));

                require("content/content_" . $_GET['activePage'] . ".php");
            }
            else
            {
                redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => 'Il faut être administrateur pour voir cette page !'), true);
                //header('Location: index.php?activePage=error&msg=Il faut être administrateur pour voir cette page !');
            }
        }
        else
        {

            if(isset($_SESSION['loggedIn']))
            {
                redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => 'Cette page ne vous est pas accessible en étant connecté !'), true);
                //header('Location: index.php?activePage=error&msg=Cette page ne vous est pas accessible en étant connecté');
            }
            else
            {
                redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => 'Vous devez être connecté pour voir cette page !'), true);
            }
        }
    }
}

// Pas d'activePage ? On renvoie sur l'index
else
{
    header('Location: index.php?activePage=index');
}

if(isset($_POST['tip']))
{
    $headerNotif = array("success" => "Succès !", "error" => "Erreur !", "warning" => "Attention !");
    $titleNotif = array_key_exists($_POST['tip'], $headerNotif) ? $headerNotif[$_POST['tip']] : "Erreur !";
    $typeNotif  = array("success" => "success", "error" => "danger", "warning" => "warning");

    $msgNotif = "";

    if(!isset($_POST['msg']))
        $msgNotif = "Message";
    else
        $msgNotif = $_POST['msg'];

    echo '<script type="text/javascript">$.notify({
	title: "<strong>' . $titleNotif . '</strong>",
	message: "<br/>' . $msgNotif . '"
},{
	type: "' . (array_key_exists($_POST['tip'], $typeNotif) ? ($typeNotif[$_POST['tip']]) : 'danger') . '"
});</script>';
}



generateHTMLFooter();
?>
