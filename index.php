<!DOCTYPE html>
<html>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Projet</title>

        <!-- CSS Bootstrap -->
        <link href="css/bootstrap.css" rel="stylesheet">
        <!-- CSS Perso -->
        <link href="css/perso.css" rel="stylesheet">

        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">
                    <span class="glyphicon glyphicon-flash" aria-hidden="true"></span>
                </a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <!--<li class="active"><a href="#">Home</a></li>
                     <li><a href="#">Page 1</a></li>
                    <li><a href="#">Page 2</a></li> 
                    <li><a href="#">Page 3</a></li> -->
                </ul>
                <div class="nav navbar-nav navbar-right">
                    <form class="navbar-form navbar-right" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" name="username" placeholder="Utilisateur">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password"  placeholder="Mot de passe">
                        </div>
                        <button type="submit" class="btn btn-default">Login Frankiz</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>


    

        <div class="jumbotron" style="padding:20px">
            <h2>ショットガン</h2>
            <blockquote>
                <p>« <i>C'est que de la chance...</i> »</p>
                <footer style="text-align: left">Un rageux</footer>
            </blockquote>
        </div>
        <div class="container-fluid" style="background-color: #f7ecb5; margin: 70px">
               Hello, the current day is <?php echo date("l"); ?>
        </div>




        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.js"></script>

</html>