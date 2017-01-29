<?php

if(isset($_POST['submittedRegister']))
{
    if(isset($_POST["formerPassword"]) && $_POST["formerPassword"] != "" &&
            isset($_POST["inputNewPassword"]) && strlen($_POST["inputNewPassword"]) >= 6 &&
            isset($_POST["inputNewPasswordConfirm"]) && strlen($_POST["inputNewPasswordConfirm"] != "") >= 6)
    {
        $user = utilisateur::getUtilisateur($mysqli, $_SESSION["mailUser"]); // Comment avoir accès à son login s'il est connecté

        if($user)
        {
            if($_POST["up2"] == $_POST["up3"])
            {
                if(utilisateur::testerMdp($mysqli, $user, $_POST['formerPassword'])) // Tout se passe bien
                {
                    $stmt = $mysqli->prepare("UPDATE `utilisateurs` SET `mdp`=? WHERE mail=?");
                    $stmt->bind_param('ss', md5($_POST['inputNewPassword']), $_SESSION["mailUser"]);
                    $stmt->execute();
                    echo("mot de passe modifié");
                    $stmt->close();
                    header('Location: http://127.0.0.1/shotgun.bin/index.php?activePage=index');
                    exit();
                }
                else // Mot de passe incorrect
                {
                    echo("Mot de passe invalide");
                }
            }
            else // Echec concordancent des mdp
            {
                echo('Les mots de passe ne correspondent pas');
            }
        }
        else
        {
            echo ("Votre compte n'est pas référencé");
        }
    }
    
    else
        redirectWithPost("index.php?activePage=changePassword", array('tip' => 'error', 'msg' => "Impossible de créer un compte utilisateur avec ces identifiants !"));
}
else
{

    echo <<<FIN

        <div class ='container-fluid titlepage' > <h1>Changer de mot de passe</h1> </div><br/><br/>
       <div class="container center-block" style="width:100%; background-color: #ffffff">
          <div class="container center-block" style="padding:15px">
    
            <form data-toggle="validator" id='register' action='index.php?activePage=content_changePassword' method='post'>
            <input type='hidden' name='submittedRegister' id='submittedRegister' value='1'/>
            <div class="form-group">
                <label for="inputFormerPassword" class="cols-sm-2 control-label">Mot de passe actuel</label>
                <div class="cols-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" data-minlength="6" class="form-control" name="formerPassword" id="inputFormerPassword" placeholder="Mot de passe" required/>
                    </div>
                </div>
            </div>
            <br/>
        
            <div class="form-group">
                <label for="inputNewPassword" class="cols-sm-2 control-label">Mot de passe</label>
                <div class="cols-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input pattern=".+" type="password" data-minlength="6" class="form-control" name="inputNewPassword" id="inputNewPassword" placeholder="Mot de passe" required/>
                    </div>
                </div>
            </div>
            <br/>

            <div class="form-group">
                <label for="inputPasswordConfirm" class="cols-sm-2 control-label">Confirmer le mot de passe</label>
                <div class="cols-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input pattern=".+" type="password" class="form-control" id="inputPasswordConfirm" name="inputNewPasswordConfirm" data-match="#inputNewPassword" data-match-error="Les deux mots de passe ne coïncident pas" placeholder="Confirmer le mot de passe" required/>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
            </div>

        <div class="form-group ">
            <button type="submit" class="btn btn-primary btn-lg btn-block login-button">Enregistrer</button>
        </div>

            </form>
        </div>
        </div>
FIN;
}
?>