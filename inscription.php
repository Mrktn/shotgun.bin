<?php

require('reponse_de_utilisateur.php');
class inscription
{
    public $id;
    public $id_shotgun;
    public $mail_user;
    public $date_shotgunned;
    
    // $idShot est l'id d'un shotgun. Quels sont les paxs inscrits à ce shotgun (par ordre d'inscription)
    public static function getInscriptionsIn($mysqli, $idShot)
    {
        $a = array();
        $query = "SELECT * FROM inscription AS ins WHERE ins.id_shotgun = $idShot ORDER BY ins.date_shotgunned ASC;";
        
        $result = $mysqli->query($query);
        
        if(!$result)
            die($mysqli->error);

        while(($row = $result->fetch_object('inscription')))
        {
            $a[] = $row;
        }
        
        return $a;
    }
    
    // Est-ce que $mail est déjà enregistré au shotgun $idShot ?
    public static function userIsRegistered($mysqli, $idShot, $mail)
    {
        if(!isValidPolytechniqueEmail($mail))
            header('Location: index.php?activePage=error&msg=Erreur fatale');

        $query = "SELECT * FROM inscription AS ins WHERE ins.id_shotgun=$idShot AND ins.mail_user='$mail';";

        $result = $mysqli->query($query);
        
        if(!$result)
            die($mysqli->error);

        return ($result->num_rows != 0);
    }
    
    // $idShot est déjà vérifié plus que de raison
    // $user est récupéré dans la session courante donc safe
    // $answers est associatif $idquestion => array{[$idréponse, $texte si libre]} et déjà vérifié
    public static function doInscription($mysqli, $idShot, $mailUser, $answers)
    {
        //insertReponseUtilisateur($mysqli, $mailUser, $idReponse, $texte)
        $currdate = date("Y-m-d H:i:s");
        // Étape 1 : ajouter une ligne à inscription (aka shotgunner)
        $stmt = $mysqli->prepare("INSERT INTO inscription (id_shotgun, mail_user, date_shotgunned) VALUES (?, ?, ?)");

        $stmt->bind_param('iss', $idShot, $mailUser, $currdate);
        
        if(!$stmt->execute())
            return false;
        
        // The id of the newly created inscription !
        $idCreatedInscription = $stmt->insert_id;
        // À partir d'ici on est bons, c'est parti pour l'étape 2 :
        // implémenter les réponses de l'utilisateur en les ajoutant à reponse_de_utilisateur
        
        //FIXME: checker que les requêtes se passent bien
        foreach($answers as $idq => $r_array)
        {
            foreach($r_array as $idr)
            {
                reponse_de_utilisateur::insertReponseUtilisateur($mysqli, $idCreatedInscription, $mailUser, $idr[0], $idr[1]);
            }
        }
        
        return true;
    }
    
    public static function doDesinscription($mysqli, $idShot, $mailUser)
    {
        $stmt = $mysqli->prepare("DELETE FROM inscription WHERE id_shotgun = ? AND mail_user = ? ");
        $stmt->bind_param('is', $idShot, $mailUser);
        
        if(!$stmt->execute())
            return false;
        
        return true;
    }
    

}