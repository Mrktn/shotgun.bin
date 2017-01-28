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
                        <button style="margin-right:10px" class="btn btn-default" name="login" type="submit">Login</button>
END;
                        //echo '<button  class="btn btn-default">Login Frankizzz</button>';
    echo'</form>';
}

// Affuche un formulaire de déconnexion
function printLogoutForm()
{
    echo <<<FIN
    <a href="index.php?activePage=changePassword"><span style="cursor:pointer;display:block;font-size:40px;color:#9ca5ad;margin-right:10px;padding-top:5px" class="navbar-right glyphicon glyphicon-cog"></span></a>
    <form class="navbar-form navbar-right" action="index.php?activePage=index&todo=logout" method="post">
    <button style="margin-right:10px" name="logout" type="submit" class="btn btn-default"> Déconnexion </button>
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
function generateNavbarSimpleUser($activePage)
{
    global $navbarSimpleUser;
    global $titleNavbar;

    foreach($navbarSimpleUser as $p)
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
function generateNavbarAdmin($activePage)
{
    global $navbarAdmin;
    global $titleNavbar;

    foreach($navbarAdmin as $p)
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
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
END;

    echo <<<END
    <div class="navbar-header">
    
      <a class="navbar-brand" href="#">
    <audio style="display:none" id="sound1" src="resources/thunder.mp3" preload="auto"></audio>
                    <span onclick="document.getElementById('sound1').play();" class="glyphicon glyphicon-flash" style="color:yellow"></span>
                </a>
    </div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
END;

    if($loggedin)
    {
        if($_SESSION['isAdmin'])
        {
            generateNavBarAdmin($activePage == 'error' || $activePage == 'info' ? 'index' : $activePage);
        }
        else
        {
            generateNavBarSimpleUser($activePage == 'error' || $activePage == 'info' ? 'index' : $activePage);
        }
    }
    else
    {
        generateNavBarLoggedOut($activePage == 'error' || $activePage == 'info' ? 'index' : $activePage);
    }

    echo "</div></div></div></div>";
    echo '<div class="wide">
  	<div class="col-xs-5 line"><hr></div>
    <div class="col-xs-2 logo">shotgun.bin</div>
    <div class="col-xs-5 line"><hr></div>
</div>';
}

function generateHTMLHeader($title)
{
    echo <<<END
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>$title</title>

        <link rel="shortcut icon" type="image/x-icon" href="resources/favicon.ico" />
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="dynatable/jquery.dynatable.css" rel="stylesheet">
        <link href="css/perso.css" rel="stylesheet">
        <link href="css/dynatable.css" rel="stylesheet">
        <link href="css/animate.css" rel="stylesheet">

        <script type="text/javascript" src="js/jquery191.js"></script>
        <script type="text/javascript" src="js/validator.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/javascriptPerso.js"></script>
        <script type="text/javascript" src="js/refresher.js"></script>
        <script type="text/javascript" src="dynatable/jquery.dynatable.js"></script>
        <script type="text/javascript" src="js/shotgunForm.js"></script>
        <script type="text/javascript" src="js/readmore.min.js"></script>
        <script type="text/javascript" src="js/moment-with-locales.min.js"></script> 
        <script type="text/javascript" src="js/bootstrap-datetimepicker.js"></script> 
        <script type="text/javascript" src="js/bootstrap-notify.min.js"></script>
        <script type="text/javascript" src="js/redirect.js"></script>
   
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js" defer></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js" defer></script>
        <![endif]-->
    </head>
    <body>
END;
}

function generateHTMLFooter()
{
    echo <<<END
    <div id="footer">
	<p> Réalisé par Antoine Balestrat et Marc Revol, élèves de la promotion X2015 </p> 	
        </div>
</body>
</html>
END;
}
?>


