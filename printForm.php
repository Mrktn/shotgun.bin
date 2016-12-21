<?php

// Contient tous les formulaires 
function printLoginForm($askedPage) { // Affiche un formulaire de connexion
    echo <<<END
                    <form class="navbar-form navbar-right" action="index.php?name=$askedPage&todo=login" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="mail" placeholder="E-mail" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password"  placeholder="Mot de passe" required>
                        </div>
                        <button class="btn btn-default" name="logAdmin" type="submit">Login admin</button>
                        <button name="logFrankiz" type="submit" class="btn btn-default">Login Frankiz</button>
                    </form>
END;
}
function printLogoutForm($askedpage) { // Affuche un formulaire de déconnexion
    echo <<<FIN
    <form action="index.php?name=$askedpage&todo=logout" method = "post">
    <p><input type ="submit" value="Deconnexion"></p>
    </form>
FIN;
}
function generateNavBar($page) { // Affiche la Navbar de connexion ou de déconnexion suivant le statut de l'utilisateur, le paramètre permet de conserver la page sur laquelle l'utilisateur navigue.
    // A penser pour après: Si l'utilisateur se déconnecte il faut vérifier qu'il a toujours le droit d'être sur la page, si ce n'est pas le cas, il faut le renvoyer à l'accueil.
    // A utiliser après Html header impérativement.
    echo <<<END
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
END;
        $menuarray = array( "index.php" => "Accueil", "register.php" => "S'enregistrer"); // A mettre ailleurs en global non?
        foreach($menuarray as $p => $t) {
            echo '<li' . ($p == $page ? ' class="active"' : '') . "><a href=\"$p\">$t</a></li>";
        }
        echo <<<END
            </ul>
            <div class="nav navbar-nav navbar-right">
END;
        if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]) {
            printLogoutForm($page);
        }
        else {
            printLoginForm($page);
        }
    echo <<<END
                </div>
            </div>
        </div>
    </nav>
END;
}
?>


