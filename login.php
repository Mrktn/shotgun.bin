<?php

$logged_in = false;
$auth = NULL;

function do_login() {
    global $auth;
    global $logged_in;

    $ret = NULL;
    if (!isset($_GET['response']))
        $ret = frankiz_do_auth();

    if ($ret != NULL) {
        $auth = frankiz_get_response();
        $logged_in = true;
        header("Location:index.php?validateLogin=" . "Bienvenue " . urlencode($auth['names']));
    }
}

// et voila !
// les données sont stockées dans $auth = array(key => value, ...);