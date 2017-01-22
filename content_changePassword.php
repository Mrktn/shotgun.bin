<?php

require('utils.php');
require('database.php');
require('utilisateur.php');
//generateHTMLHeader("S'enregistrer");
//generateMenu("content_changePassword.php");
$_SESSION["mail"] = 'tesuto@polytechnique.edu';
$mysqli = mysqli_connect();

if (isset($_POST["formerPassword"]) && $_POST["formerPassword"] != "" &&
        isset($_POST["inputNewPassword"]) && $_POST["inputNewPassword"] != "" &&
        isset($_POST["inputNewPasswordConfirm"]) && $_POST["inputNewPasswordConfirm"] != "")
{
    $user = utilisateur::getUtilisateur($mysqli, $_SESSION["mail"]); // Comment avoir accès à son login s'il est connecté

    if ($user)
    {
        if ($_POST["up2"] == $_POST["up3"])
        {

            if (utilisateur::testerMdp($mysqli, $user, $_POST['formerPassword'])) // Tout se passe bien
            {
                $stmt = $mysqli->prepare("UPDATE `utilisateurs` SET `mdp`=? WHERE mail=?");
                $stmt->bind_param('ss',md5($_POST['inputNewPassword']), $_SESSION["mail"]);
                $form_values_valid = true;
                echo("mot de passe modifié");
                $stmt->close();
                header('Location: http://127.0.0.1/shotgun.bin/index.php?activePage=index');
                exit();
            } else // Mot de passe incorrect
            {
                echo("Mot de passe invalide");
            }
        } else // Echec concordancent des mdp
        {
            echo('Les mots de passe ne correspondent pas');
    }} else
        {
         echo ("Votre compte n'est pas référencé");   
        }
}

echo <<<FIN

        <div class="container">
            <form data-toggle="validator" role="form" id='register' action='content_changePassword.php' method='post'>
        
            <div class="form-group">
                <label for="inputFormerPassword" class="cols-sm-2 control-label">Mot de passe actuel</label>
                <div class="cols-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
                        <input type="password" data-minlength="6" class="form-control" name="formerPassword" id="inputFormerPassword" placeholder="Mot de passe" required/>
                    </div>
                </div>
            </div>
        
            <div class="form-group">
                <label for="inputNewPassword" class="cols-sm-2 control-label">Mot de passe</label>
                <div class="cols-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
                        <input pattern=".+" type="password" data-minlength="6" class="form-control" name="inputNewPassword" id="inputNewPassword" placeholder="Mot de passe" required/>
                    </div>
                </div>
            </div>
        
            <div class="form-group">
                <label for="inputPasswordConfirm" class="cols-sm-2 control-label">Confirmer le mot de passe</label>
                <div class="cols-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
                        <input pattern=".+" type="password" class="form-control" id="inputPasswordConfirm" name="inputNewPasswordConfirm" data-match="#inputNewPassword" data-match-error="Les deux mots de passe ne coïncident pas" placeholder="Confirmer le mot de passe" required/>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
            </div>

        <div class="form-group ">
            <button type="submit" class="btn btn-primary btn-lg btn-block login-button">Register</button>
        </div>

            </form>
        </div>
FIN;

?>

<?php
//generateHTMLFooter();
?>