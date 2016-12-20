<?php
    require('utils.php');
    generateHTMLHeader("shotgun.bin");
    generateMenu("index.php");
?>
        <?php
       
        
        // Si l'utilisateur tente le login admin
        if(isset($_GET['error']))
            echo "<div class=\"container-fluid\" style=\"background-color: #f1545b; margin: 70px\">{$_GET["error"]}</div>";
        if(isset($_POST['logAdmin']))
            redirectError("Pas encore implémenté le login admin !");
        if(isset($_POST['logFrankiz']))
            redirectError("Pas encore implémenté le login Frankiz !");
        if(isset($_GET['validateLogin']))
            echo "Aïe Aïe, logged in via Frankiz !!";
            //do_login();
        echo'<div class="container-fluid" style="background-color: #f7ecb5; margin: 70px">Hello, today is ' . date("l") . '</div>';

    generateHTMLFooter();
?>
