<?php

session_start();
// Un utilisateur pas logged in ne peut pas exécuter quoi que ce soit
if(!isset($_SESSION['loggedIn']))
    header('Location: index.php?activePage=error&msg=Vous n\'êtes pas connecté !');

// Si on a un truc à faire...
if(isset($_POST['todo']))
{
    echo $_POST['todo'];
}

else
    header('Location: index.php?activePage=error&msg=Aucune opération à effectuer !');