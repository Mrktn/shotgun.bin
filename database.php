<?php

class Database
{

    public static function connect()
    {
        $mysqli = new mysqli("127.0.0.1", "root", "", "shotgun");
        /* $dsn = 'mysql:dbname=shotgun;host=127.0.0.1';
          $user = 'root';
          $password = '';
          $dbh = null; */

        if($mysqli->connect_errno)
        {
            echo "Echec lors de la connexion à MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        
        else
        {
            return $mysqli;
        }
        /*try
        {
            $dbh = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            echo 'Connexion échouée : ' . $e->getMessage();
            exit(0);
        }
        return $dbh;*/
    }

}