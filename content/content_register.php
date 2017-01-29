<?php
// Cette page permet à l'utilisateur de s'enregistrer

// Traitement le formulaire d'inscription
if(isset($_POST['submittedRegister']))
{
    if(!isset($_POST['inputPasswordRegister']) || !isset($_POST["inputPasswordConfirmRegister"]) || !isset($_POST['inputEmailRegister']) || ($_POST["inputPasswordRegister"] != $_POST["inputPasswordConfirmRegister"]) || !isValidPolytechniqueEmail($_POST["inputEmailRegister"]) || (strlen($_POST['inputPasswordRegister']) < 6) || (strlen($_POST['inputPasswordConfirmRegister']) < 6))
        redirectWithPost("index.php?activePage=register", array('tip' => 'error', 'msg' => "Merci de réessayer avec des valeurs correctes !"));
    else
    {
        $password = $_POST['inputPasswordRegister'];
        $email = $_POST['inputEmailRegister'];

        // Clé secrète de l'utilisateur, en théorie pour vérifier l'adresse mail, non utilisé dans la version actuelle
        $key = md5($password . $email . date('mY'));

        if(utilisateur::insererUtilisateur(DBi::$mysqli, $email, 0, $key, $password, 0))
            redirectWithPost("index.php?activePage=index", array('tip' => 'success', 'msg' => "Votre compte a été créé, vous pouvez maintenant vous connecter !"));
        else
           redirectWithPost("index.php?activePage=register", array('tip' => 'error', 'msg' => "Impossible de créer un compte utilisateur avec ces identifiants !"));
    }
}
else
{
// Affichage du formulaire d'inscription
?>
    <div class ='container-fluid titlepage' > <h1>S'enregistrer</h1> </div><br/><br/>
       <div class="container center-block" style="width:100%; background-color: #ffffff">
          <div class="container center-block" style="padding:15px">
    <form data-toggle="validator" id='register' action='index.php?activePage=register' method='post'>
        <input type='hidden' name='submittedRegister' id='submittedRegister' value='1'/>
        <div class="form-group">
            <label for="inputEmailRegister" class="cols-sm-2 control-label">Votre adresse mail @polytechnique.edu</label>
            <div class="cols-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                    <input pattern=".+@polytechnique\.edu" class="form-control" id="inputEmailRegister" name="inputEmailRegister" placeholder="Adresse mail @polytechnique.edu" data-error="Seules les adresses en polytechnique.edu sont tolérées" required/>
                </div>
            </div>
            <br/>
        
            <label for="inputPasswordRegister" class="cols-sm-2 control-label">Mot de passe</label>
            <div class="cols-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input pattern=".+" type="password" data-minlength="6" class="form-control" name="inputPasswordRegister" id="inputPasswordRegister" placeholder="Mot de passe" required/>
                </div>
            </div>
            <br/>
    
            <label for="inputPasswordConfirmRegister" class="cols-sm-2 control-label">Confirmer le mot de passe</label>
            <div class="cols-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
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
    <?php
};
