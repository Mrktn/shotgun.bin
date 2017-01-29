<?php
// Sert à implémenter la classe question reliée à la base de donnée ainsi que toutes les fonctions utilisant la classe. Inscription permet de relier les utilisateurs aux shotguns_event afin de savoir qui est inscrit à quoi
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

        $stmt = $mysqli->prepare("SELECT * FROM inscription AS ins WHERE ins.id_shotgun = ? ORDER BY ins.date_shotgunned ASC;");

        if(!$stmt || !($stmt->bind_param('i', $idShot)) || !$stmt->execute())
            die('Erreur irrécupérable dans getInscriptionsIn');

        $result = $stmt->get_result();

        if(!$result)
            die('Erreur irrécupérable dans getInscriptionsIn');

        $stmt->close();

        while(($row = $result->fetch_object('inscription')))
            $a[] = $row;

        return $a;
    }

    // Est-ce que $mail est déjà enregistré au shotgun $idShot ?
    public static function userIsRegistered($mysqli, $idShot, $mail)
    {
        $stmt = $mysqli->prepare("SELECT * FROM inscription AS ins WHERE ins.id_shotgun = ? AND ins.mail_user = ?;");

        if(!$stmt || !($stmt->bind_param('is', $idShot, $mail)) || !$stmt->execute() || !($result = $stmt->get_result()))
            die("Erreur fatale dans userIsRegistered");

        $stmt->close();

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

        $currdate = date("Y-m-d H:i:s");

        // Étape 1 : ajouter une ligne à inscription (aka shotgunner)
        $stmt = $mysqli->prepare("INSERT INTO inscription (id_shotgun, mail_user, date_shotgunned) VALUES (?, ?, ?)");

        if(!$stmt || !($stmt->bind_param('iss', $idShot, $mailUser, $currdate)) || !($stmt->execute()))
            die('Erreur fatale dans doInscription');

        // L'id du shotgun nouvellement créé
        $idCreatedInscription = $stmt->insert_id;
        $stmt->close();

        // Étape 2 : implémenter les réponses de l'utilisateur en les ajoutant à reponse_de_utilisateur
        foreach($answers as $idq => $r_array)
        {
            foreach($r_array as $idr)
            {
                // Si on n'arrive pas à faire une simple insertion comme celle-ci c'est que c'est irrécupérable
                if(!reponse_de_utilisateur::insertReponseUtilisateur($mysqli, $idCreatedInscription, $mailUser, $idr[0], $idr[1]))
                    die("Erreur fatale dans doInscription");
            }
        }

        return true;
    }

    public static function doDesinscription($mysqli, $idShot, $mailUser)
    {
        $stmt = $mysqli->prepare("DELETE FROM inscription WHERE id_shotgun = ? AND mail_user = ? ");

        if(!$stmt || !($stmt->bind_param('is', $idShot, $mailUser)) || !($stmt->execute()))
            die("Erreur fatale dans doDesinscription");

        return true;
    }

    // Retourne l'inscription de quelqu'un à un shotgun sous forme exhaustive (intitulé des questions avec
    // en vis-à-vis l'intitulé des réponses, grâce à une requête d'outre-tombe
    // Diablement efficace pour créer l'array des données à télécharger
    public static function getComprehensiveInscription($mysqli, $idShot, $mailUser)
    {
        $a = array();
        $stmt = $mysqli->prepare(
                "SELECT ins.date_shotgunned,quest.id,quest.intitule AS intitule_question,quest.type AS question_type,rep.id,GROUP_CONCAT(rep.intitule SEPARATOR ';') AS intitule_reponses,repuser.texte
                    FROM inscription AS ins, question AS quest, reponse AS rep, reponse_de_utilisateur AS repuser
                    WHERE ins.mail_user = ? AND quest.id_shotgun = ? AND rep.id_question=quest.id AND repuser.id_reponse=rep.id AND repuser.id_inscription=ins.id
                    GROUP BY quest.id
                    ORDER BY quest.id ASC"
        );

        if(!$stmt || !($stmt->bind_param('si', $mailUser, $idShot)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die("Erreur fatale dans getComprehensiveInscription");

        $stmt->close();

        while($row = $result->fetch_array(MYSQLI_ASSOC))
            $a[] = $row;

        return $a;
    }

}