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
        $shotgun = shotgun_event::shotgunGet($mysqli, $idShot);
        
        // Au cas où le shotgun aurait été retiré dans l'intervalle de temps.
        if($shotgun == null)
            return false;
        
        // Si on est à la limite du nombre de places, ce n'est pas la peine d'essayer !
        if($shotgun->nb_places == 0 ? false : (shotgun_event::getNumInscriptions($mysqli, $idShot) >= $shotgun->nb_places))
            return false;

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
                reponse_de_utilisateur::insertReponseUtilisateur($mysqli, $idCreatedInscription, $mailUser, $idr[0], $idr[1]);
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
    
    // Retourne l'inscription de quelqu'un à un shotgun sous forme exhaustive (intitulé des questions avec
    // en vis-à-vis l'intitulé des réponses, grâce à une requête d'outre-tombe
    // Diablement efficace pour créer l'array des données à télécharger
    public static function getComprehensiveInscription($mysqli, $idShot, $mailUser)
    {
        $stmt = $mysqli->prepare(
        "SELECT ins.date_shotgunned,quest.id,quest.intitule AS intitule_question,quest.type AS question_type,rep.id,GROUP_CONCAT(rep.intitule SEPARATOR ';') AS intitule_reponses,repuser.texte
        FROM inscription AS ins, question AS quest, reponse AS rep, reponse_de_utilisateur AS repuser
        WHERE ins.mail_user = ? AND quest.id_shotgun = ? AND rep.id_question=quest.id AND repuser.id_reponse=rep.id AND repuser.id_inscription=ins.id
        GROUP BY quest.id
        ORDER BY quest.id ASC");
        
        $stmt->bind_param('si', $mailUser, $idShot);
        $a = array();
        if(!$stmt->execute())
            die($stmt->error);
        
        $result = $stmt->get_result();
        while($row = $result->fetch_array(MYSQLI_ASSOC))
            $a[] = $row;
        return $a;
    }
    

}