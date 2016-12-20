<?php
require('utils.php');
require('database.php');
generateHTMLHeader("S'enregistrer");
generateMenu("register.php");

function mail_utf8($to, $from_user, $from_email, $subject = '(No subject)', $message = '') {
    $from_user = "=?UTF-8?B?" . base64_encode($from_user) . "?=";
    $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";

    $headers = "From: $from_user <$from_email>\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";

    return mail($to, $subject, $message, $headers);
}

if (isset($_POST['submitted'])) {
    if (($_POST["inputPassword"] != $_POST["inputPasswordConfirm"]) || !preg_match("/.+@polytechnique\.edu/", $_POST["inputEmail"])) {
        echo "Merci de ne pas essayer de nous hacker";
    } else {
        $password = mysql_real_escape_string($_POST['inputPassword']);
        $email = mysql_real_escape_string($_POST['inputEmail']);

        //mail_utf8("antoine.balestrat@gmail.com", "kikoo@lol.com", "kikoo@lol.com", $subject = '(No subject)', $message = 'TG');
        // Clé secrète de l'utilisateur
        $key = md5($password . $email . date('mY'));

        $dbh = Database::connect();
        // INSERT INTO `utilisateur` (`mail`, `active`, `code_secret`, `mdp`) VALUES ('a@polytechnique.edu', '0', '45188e9cce62fa01578fbf40daa8ec40', '0b4e7a0e5fe84ad35fb5f95b9ceeac79');

        $query = 'INSERT INTO `utilisateur` (`mail`, `active`, `code_secret`, `mdp`) VALUES ("' . $email . '",1,"' . $key . '", "' . md5($password) . '")';
        $sth = $dbh->prepare($query);
        if (!$sth->execute()) {
            echo 'Impossible de vous ajouter à la base de données !';
        }

        /*$message = "yo";
        // Envoi du mail

        echo "Email: " . $email . "<br/>";
        echo $message;

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: shotgun <postmaster@localhost>' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        //mail($email, 'Confirmez votre inscription sur shotgun.bin', $message, $headers);
        mail_utf8("antoine.balestrat@polytechnique.edu", "kikoo@lol.com", "kikoo@lol.com", $subject = '(No subject)', $message = 'TG');*/
    }
}
?>
<div class="container">
    <form data-toggle="validator" role="form" id='register' action='register.php' method='post'>
        <input type='hidden' name='submitted' id='submitted' value='1'/>
        <div class="form-group">
            <label for="email" class="cols-sm-2 control-label">Votre adresse mail @polytechnique.edu</label>
            <div class="cols-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i></span>
                    <input pattern=".+@polytechnique\.edu" class="form-control" name="inputEmail" placeholder="Adresse mail @polytechnique.edu" data-error="Seules les adresses en polytechnique.edu sont tolérées" required/>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="cols-sm-2 control-label">Mot de passe</label>
            <div class="cols-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
                    <input pattern=".+" type="password" data-minlength="6" class="form-control" name="inputPassword" id="inputPassword" placeholder="Mot de passe" required/>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="confirm" class="cols-sm-2 control-label">Confirmer le mot de passe</label>
            <div class="cols-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
                    <input pattern=".+" type="password" class="form-control" id="inputPasswordConfirm" name="inputPasswordConfirm" data-match="#inputPassword" data-match-error="Les deux mots de passe ne coïncident pas" placeholder="Confirmer le mot de passe" required/>
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div>

        <div class="form-group ">
            <button type="submit" class="btn btn-primary btn-lg btn-block login-button">Register</button>
        </div>

    </form>
</div>

<?php
generateHTMLFooter();
?>

