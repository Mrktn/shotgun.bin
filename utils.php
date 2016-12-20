<?php

require_once 'login.php';

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

function generateMenu($pagename) {
    global $logged_in;

    if (!$logged_in) {
        echo <<<END
    <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
END;
        $menuarray = array( "index.php" => "Accueil", "register.php" => "S'enregistrer");
        foreach($menuarray as $p => $t) {
            echo '<li' . ($p == $pagename ? ' class="active"' : '') . "><a href=\"$p\">$t</a></li>";
        }
        echo <<<END
                </ul>
                <div class="nav navbar-nav navbar-right">
                    <form class="navbar-form navbar-right" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="username" placeholder="Utilisateur">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password"  placeholder="Mot de passe">
                        </div>
                        <button class="btn btn-default" name="logAdmin" type="submit">Login admin</button>
                        <button name="logFrankiz" type="submit" class="btn btn-default">Login Frankiz</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
END;
    }
}
/*
<form action="" method="post">
<label>UserName :</label>
<input id="name" name="username" placeholder="username" type="text">
<label>Password :</label>
<input id="password" name="password" placeholder="**********" type="password">
<input name="submit" type="submit" value=" Login ">
<span><?php echo $error; ?></span>
</form>*/

function generateHTMLFooter() {
    echo <<<END
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
</body>
</html>
END;
}
