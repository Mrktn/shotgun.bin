<?php
session_name("thesess"); // Session : pour la persistance : cookies qui perdure savoir si on est co ou pas
// ne pas mettre d'espace dans le nom de session !
session_start();
session_id('TEST');
setlocale(LC_ALL, 'fr_FR.utf8','fra');
if(!isset($_SESSION['initiated']))
{
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}

// Décommenter la ligne suivante pour afficher le tableau $_SESSION pour le debuggage
//print_r($_SESSION);

require('DBi.php');
DBi::connect();

require('logInOut.php');
require('printForm.php');
require('globalvar.php');
require('utils.php');
require('shotgun_event.php');
require('inscription.php');
require('question.php');
require('reponse.php');

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
}


// Si on a du boulot à faire du point de vue des shotguns (fermer, ouvrir, activer, désactiver, supprimer)
if(isset($_GET['todoShotgunIt']))
{
    $action = $_GET['todoShotgunIt'];
    
    if(!isset($_GET['idShotgun']) || !isset($_SESSION['loggedIn']))
        header('Location: index.php?activePage=error&msg=Accès non autorisé !');

    if($action == "closeShotgun" || $action == 'openShotgun')
    {
        if(shotgun_event::userMayCloseOrOpenShotgun(DBi::$mysqli, $_GET['idShotgun'], $_SESSION['mailUser'], $_SESSION['isAdmin']))
        {
            shotgun_event::updateShotgun(DBi::$mysqli, $_GET['idShotgun'], $action);
        }
        
        else
            header('Location: index.php?activePage=error&msg=Vous n\'avez pas les permissions pour fermer / ouvrir ce shotgun !');
    }
    
    // Seuls les admins peuvent delete
    else if($action == 'deleteShotgun')
    {
        if($_SESSION['isAdmin'])
            shotgun_event::updateShotgun(DBi::$mysqli, $_GET['idShotgun'], 'deleteShotgun');
        else
            header('Location: index.php?activePage=error&msg=Il faut être admin pour supprimer un shotgun !');
    }
    
    // Il faut être admin pour activer / désactiver (autoriser / interdire)
    else if($action == 'activateShotgun' || $action == 'disableShotgun')
    {
        if($_SESSION['isAdmin'])
            shotgun_event::updateShotgun(DBi::$mysqli, $_GET['idShotgun'], $action);
        else
            header('Location: index.php?activePage=error&msg=Il faut être admin pour activer / désactiver un shotgun !');
    }
    
    else if($action == 'suscribe' || $action == 'unsuscribe')
    {
        
    }
    else
        header('Location: index.php?activePage=error&msg=Action interdite !');
}

// Si on a un truc dans l'URL qui dit ce qu'on doit afficher, on le fait
if(isset($_GET['activePage']))
{
    // Si ça n'est pas une clé du tableau title, c'est que ce n'est pas une page qui existe (l'utilisateur a pu mettre activePage=blblbl),
    // on va simplement le renvoyer sur l'index pour le punir
    if(!array_key_exists($_GET['activePage'], $title))
    {
        // Recharge la page mais avec activePage=index
        header('Location: index.php?activePage=index');
    }

    // Sinon, c'est une page qui existe
    else
    {
        // C'est là qu'on gère les erreurs
        if($_GET['activePage'] == 'error')
        {
            generateHTMLHeader("Erreur");
            generateNavBar($_GET['activePage'], isset($_SESSION['loggedIn']));

            require('content_error.php');
        }

        // Ici on gère les informations à l'utilisateur
        else if($_GET['activePage'] == 'info')
        {
            generateHTMLHeader("Information");
            generateNavBar($_GET['activePage'], isset($_SESSION['loggedIn']));

            require('content_info.php');
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

                require("content_" . $_GET['activePage'] . ".php");
            }
            
            else
            {
                header('Location: index.php?activePage=error&msg=Il faut être administrateur pour voir cette page !');
            }
        }
        else
        {
            
            if(isset($_SESSION['loggedIn']))
            {
                header('Location: index.php?activePage=error&msg=Cette page ne vous est pas accessible en étant connecté');
            }
            else
            {
                header('Location: index.php?activePage=error&msg=Vous devez être connecté pour voir cette page');
            }
        }
    }
}
// Pas d'activePage ? On renvoie sur l'index
else
{
    header('Location: index.php?activePage=index');
}

generateHTMLFooter();
?>
