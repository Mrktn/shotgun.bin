<?php

class DBi
{
    public static $mysqli;
    
    public static function connect()
    {
        DBi::$mysqli = new mysqli("127.0.0.1", "root", "", "shotgun");
        /* $dsn = 'mysql:dbname=shotgun;host=127.0.0.1';
          $user = 'root';
          $password = '';
          $dbh = null; */

        if(DBi::$mysqli->connect_errno)
        {
            echo "Echec lors de la connexion à MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
    }
}