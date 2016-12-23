<?php

require('utilisateur.php');

// opérations sur la base
function logIn($dbh)
{ // Test si le nom de compte et mot de passe sont corrects
    //print_r($_POST);
    $user = utilisateur::getUtilisateur($dbh, $_POST['mail']);
    $test = utilisateur::testerMdp($dbh, $user, $_POST['password']);
    if ($test)
    {
        $_SESSION['loggedIn'] = true; // la variable va persister au fur et à mesure de la navigation
        echo('ok');
    }
    else
    {
        unset($_SESSION['loggedIn']); // on ne veut même pas set cette variable
        echo("nom d'utilisateur ou mot de passe incorrect");
    };
}

function logOut()
{
    unset($_SESSION['loggedIn']);
}
