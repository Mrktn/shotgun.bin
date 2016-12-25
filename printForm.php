<?php

require('globalvar.php');

// Affiche un formulaire de connexion
function printLoginForm()
{
    echo <<<END
                    <form class="navbar-form navbar-right" action="index.php?activePage=index&todo=login" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="mail" placeholder="E-mail" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password"  placeholder="Mot de passe" required>
                        </div>
                        <button class="btn btn-default" name="login" type="submit">Login</button>
                        <button  class="btn btn-default">Login Frankizzz</button>
                    </form>
END;
}

// Affuche un formulaire de déconnexion
function printLogoutForm()
{
    echo <<<FIN
    <form class="navbar-form navbar-right" action="index.php?activePage=index&todo=logout" method="post">
    <button name="logout" type="submit" class="btn btn-default">Déconnexion</button>
    </form>
FIN;
}

// Génère la navbar quand on est unlogged
function generateNavBarLoggedOut($activePage)
{
    global $titleNavbar;
    global $navbarLoggedOut;

    foreach($navbarLoggedOut as $p)
    {
        $t = $titleNavbar[$p];
        echo '<li' . ($p == $activePage ? ' class="active"' : '') . "><a href=\"?activePage=$p\">$t</a></li>";
    }

    echo <<<END
            </ul>
            <div class="nav navbar-nav navbar-right">
END;

    printLoginForm();
}

// Génère la navbar quand on est logged in
function generateNavbarLoggedIn($activePage)
{
    global $navbarLoggedIn;
    global $titleNavbar;

    foreach($navbarLoggedIn as $p)
    {
        $t = $titleNavbar[$p];
        echo '<li' . ($p == $activePage ? ' class="active"' : '') . "><a href=\"?activePage=$p\">$t</a></li>";
    }

    echo <<<END
            </ul>
            <div class="nav navbar-nav navbar-right">
END;

    printLogoutForm();
}

// Génère la navbar quand on est logged in
function generateNavbarLoggedInAdmin($activePage)
{
    global $navbarLoggedIn;
    global $titleNavbar;

    foreach($navbarLoggedIn as $p)
    {
        $t = $titleNavbar[$p];
        echo '<li' . ($p == $activePage ? ' class="active"' : '') . "><a href=\"?activePage=$p\">$t</a></li>";
    }

    echo <<<END
            </ul>
            
            <div class="nav navbar-nav navbar-right"><form class="navbar-form navbar-right" action="index.php" method="get">
    <a href="index.php?activePage=manageShotguns" class="btn btn-danger" role="button">Administrer</a>
    </form>
END;
    printLogoutForm();
}

// $loggedin est un booléen qui dit si on est logué
// $activePage est la page sur laquelle on est actuellement (codage des noms de page défini dans globalvar.php : index, register, etc
function generateNavBar($activePage, $loggedin)
{

    echo <<<END
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
END;

    // Si par contre l'activePage est une erreur, par convention on met le focus sur l'accueil.
    // Contestable, on peut aussi ne pas mettre de focus.
    if($loggedin)
    {
        if($_SESSION['isAdmin'])
        {
            generateNavBarLoggedInAdmin($activePage == 'error' || $activePage == 'info' ? 'index' : $activePage);
        }
        else
        {
            generateNavBarLoggedIn($activePage == 'error' || $activePage == 'info' ? 'index' : $activePage);
        }
    }
    else
    {
        generateNavBarLoggedOut($activePage == 'error' || $activePage == 'info' ? 'index' : $activePage);
    }

    echo <<<END
                </div>
            </div>
        </div>
    </nav>
END;
}

function generateHTMLHeader($title)
{
    echo <<<END
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8" /> 
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>$title</title>

        <link href="css/bootstrap.css" rel="stylesheet">
            <link href="dynatable/jquery.dynatable.css" rel="stylesheet">
         <link href="css/perso.css" rel="stylesheet">

        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/validator.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/javascriptPerso.js"></script>
        <script type="text/javascript" src="dynatable/jquery.dynatable.js"></script>
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">
                    <span class="glyphicon glyphicon-flash" aria-hidden="true"></span>
                </a>
            </div>
END;
}

function generateHTMLFooter()
{
    echo <<<END
</body>
</html>
END;
}
?>


