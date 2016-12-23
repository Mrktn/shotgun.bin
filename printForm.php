<?php

require('globalvar.php');

// Contient tous les formulaires 
function printLoginForm($askedPage)
{ // Affiche un formulaire de connexion
    echo <<<END
                    <form class="navbar-form navbar-right" action="index.php?name=$askedPage&todo=login" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="mail" placeholder="E-mail" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password"  placeholder="Mot de passe" required>
                        </div>
                        <button class="btn btn-default" name="logAdmin" type="submit">Login</button>
                        <button name="logFrankiz" type="submit" class="btn btn-default">Login Frankiz</button>
                    </form>
END;
}

function printLogoutForm($askedpage)
{ // Affuche un formulaire de déconnexion
    echo <<<FIN
    <form class="navbar-form navbar-right" action="index.php?name=$askedpage&todo=logout" method="post">
    <button name="logout" type="submit" class="btn btn-default">Déconnexion</button>
    </form>
FIN;
}

function generateNavBarLoggedOut($activePage)
{
    global $titleNavbar;
    global $pagesLoggedOut;

    foreach ($pagesLoggedOut as $p)
    {
        $t = $titleNavbar[$p];
        echo '<li' . ($p == $activePage ? ' class="active"' : '') . "><a href=\"?activePage=$p\">$t</a></li>";
    }
    
    echo <<<END
            </ul>
            <div class="nav navbar-nav navbar-right">
END;
    
    printLoginForm($activePage);
}

function generateNavbarLoggedIn($activePage)
{
    global $pagesLoggedIn;
    global $titleNavbar;

    foreach ($pagesLoggedIn as $p)
    {
        $t = $titleNavbar[$p];
        echo '<li' . ($p == $activePage ? ' class="active"' : '') . "><a href=\"?activePage=$p\">$t</a></li>";
    }
    
    echo <<<END
            </ul>
            <div class="nav navbar-nav navbar-right">
END;
    
    printLogoutForm($activePage);
    
}

function generateNavBar($activePage, $loggedin)
{ // Affiche la Navbar de connexion ou de déconnexion suivant le statut de l'utilisateur, le paramètre permet de conserver la page sur laquelle l'utilisateur navigue.
    // A penser pour après: Si l'utilisateur se déconnecte il faut vérifier qu'il a toujours le droit d'être sur la page, si ce n'est pas le cas, il faut le renvoyer à l'accueil.
    // A utiliser après Html header impérativement.
    
    echo <<<END
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
END;
    
    if ($loggedin)
    {
        generateNavBarLoggedIn($activePage);
    }
    else
    {
        generateNavBarLoggedOut($activePage);
    }

    echo <<<END
                </div>
            </div>
        </div>
    </nav>
END;
}

function generateHTMLHeader($title) {
    echo <<<END
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>$title</title>

        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/perso.css" rel="stylesheet">

        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/validator.js"></script>
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
    
    /*echo <<<END
    <div class="jumbotron" style="padding:20px">
            <h2>ショットガン</h2>
            <blockquote>
                <p>« <i>C'est que de la chance...</i> »</p>
                <footer style="text-align: left">Un rageux</footer>
            </blockquote>
        </div>
END;*/
}

function generateHTMLFooter() {
    echo <<<END
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
</body>
</html>
END;
}


?>


