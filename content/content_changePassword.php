<?php

if(isset($_POST['submittedRegister']))
{
    if(isset($_POST["formerPassword"]) && $_POST["formerPassword"] != "" &&
            isset($_POST["inputNewPassword"]) && strlen($_POST["inputNewPassword"]) >= 6 &&
            isset($_POST["inputNewPasswordConfirm"]) && strlen($_POST["inputNewPasswordConfirm"]) >= 6)
    {
        if($_POST["inputNewPassword"] == $_POST["inputNewPasswordConfirm"])
        {
             $user = utilisateur::getUtilisateur(DBi::$mysqli, $_SESSION["mailUser"]); // Comment avoir accès à son login s'il est connecté
             if($user)
             {
                 if(utilisateur::testerMdp(DBi::$mysqli, $user, $_POST['formerPassword']))
                 {
                    if(utilisateur::updatePassword(DBi::$mysqli, $user->mail, $_POST['inputNewPassword']))
                        redirectWithPost("index.php?activePage=index", array('tip' => 'success', 'msg' => "Mot de passe changé avec succès !"));
                    else
                        redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Impossible de changer le mot de passe, contactez un administrateur."));
                 }
                 
                 else
                     redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Votre ancien mot de passe ne correspond pas !"));
             }
             else
                 redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Erreur lors de la vérification !"));
        }
        
        else
            redirectWithPost("index.php?activePage=index", array('tip' => 'error', 'msg' => "Les mots de passe ne matchent pas !"));
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
    
            <form data-toggle="validator" id='register' action='index.php?activePage=changePassword' method='post'>
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