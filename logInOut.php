<?php

require('classes/utilisateur.php');

// opérations sur la base
function logIn($mysqli)
{ // Test si le nom de compte et mot de passe sont corrects
    $user = utilisateur::getUtilisateur($mysqli, $_POST['mail']);
    $test = utilisateur::testerMdp($mysqli, $user, $_POST['password']);

    if($test)
    {
        $_SESSION['loggedIn'] = true; // la variable va persister au fur et à mesure de la navigation
        $_SESSION['isAdmin'] = $user->admin;
        $_SESSION['mailUser'] = $user->mail;
        redirectWithPost("index.php?activePage=index", array('tip' => 'success', 'msg' => "Vous êtes maintenant connecté !"), true);
    }
    else
    {
        unset($_SESSION['loggedIn']); // on ne veut même pas set cette variable
        redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Votre adresse mail ou votre mot de passe est invalide !"), true);
    };
}

function logOut()
{
    unset($_SESSION['loggedIn']);
    unset($_SESSION['isAdmin']);
    unset($_SESSION['mailUser']);
}
