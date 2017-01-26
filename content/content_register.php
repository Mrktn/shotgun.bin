<?php

if(isset($_POST['submittedRegister']))
{
    if(($_POST["inputPasswordRegister"] != $_POST["inputPasswordConfirmRegister"]) || !isValidPolytechniqueEmail($_POST["inputEmailRegister"]))
    {
        header('Location: index.php?activePage=error&msg=Merci de ne pas essayer de nous hacker ¯\_(ツ)_/¯');
    }
    else
    {
        
        $password = DBi::$mysqli->real_escape_string($_POST['inputPasswordRegister']);
        $email = DBi::$mysqli->real_escape_string($_POST['inputEmailRegister']);
        
        // Clé secrète de l'utilisateur, en théorie pour vérifier l'adresse mail
        $key = md5($password . $email . date('mY'));
        
        if(utilisateur::insererUtilisateur(DBi::$mysqli, $email, 0, $key, $password, 0))
        {
            header('Location: index.php?activePage=info&msg=Votre compte a été créé, vous pouvez maintenant vous connecter !');
        }
    }
}
else
{
    echo <<<END
<div class="container center-block" style="width:100%; background-color: #ffffff">
    <h1>S'enregistrer</h1>
 <div class="container center-block" style="padding:30px; width:70%">
    <form data-toggle="validator" role="form" id='register' action='index.php?activePage=register' method='post'>
        <input type='hidden' name='submittedRegister' id='submittedRegister' value='1'/>
        <div class="form-group">
            <label for="email" class="cols-sm-2 control-label">Votre adresse mail @polytechnique.edu</label>
            <div class="cols-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i></span>
                    <input pattern=".+@polytechnique\.edu" class="form-control" name="inputEmailRegister" placeholder="Adresse mail @polytechnique.edu" data-error="Seules les adresses en polytechnique.edu sont tolérées" required/>
                </div>
            </div>

        
            <label for="password" class="cols-sm-2 control-label">Mot de passe</label>
            <div class="cols-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
                    <input pattern=".+" type="password" data-minlength="6" class="form-control" name="inputPasswordRegister" id="inputPasswordRegister" placeholder="Mot de passe" required/>
                </div>
            </div>

            <label for="confirm" class="cols-sm-2 control-label">Confirmer le mot de passe</label>
            <div class="cols-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
                    <input pattern=".+" type="password" class="form-control" id="inputPasswordConfirmRegister" name="inputPasswordConfirmRegister" data-match="#inputPasswordRegister" data-match-error="Les deux mots de passe ne coïncident pas" placeholder="Confirmer le mot de passe" required/>
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div>

        <div class="form-group ">
            <button type="submit" class="btn btn-primary btn-lg btn-block login-button">S'enregistrer</button>
        </div>

    </form>
</div>
    </div>
END;
}
?>
