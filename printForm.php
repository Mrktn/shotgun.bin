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
?>


