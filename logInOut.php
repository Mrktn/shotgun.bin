<?php

require('utilisateur.php');

// opérations sur la base
function logIn($mysqli)
{ // Test si le nom de compte et mot de passe sont corrects
    //print_r($_POST);
    $user = utilisateur::getUtilisateur($mysqli, $_POST['mail']);
    $test = utilisateur::testerMdp($mysqli, $user, $_POST['password']);
    
    if($test)
    {
        $_SESSION['loggedIn'] = true; // la variable va persister au fur et à mesure de la navigation
        $_SESSION['isAdmin'] = $user->admin;
        header('Location: index.php?activePage=info&msg=Vous êtes maintenant connecté !');
    }
    else
    {
        unset($_SESSION['loggedIn']); // on ne veut même pas set cette variable
        header('Location: index.php?activePage=error&msg=Votre adresse mail ou votre mot de passe est invalide');
    };
}

function logOut()
{
    unset($_SESSION['loggedIn']);
}
