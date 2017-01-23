<?php

class reponse // Réponse = Choix 
{

    public $id;
    public $id_question;
    public $intitule;

    public static function getReponseFromId($mysqli, $id)
    {
        $query = "SELECT * FROM reponse WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $id);
        if (!$stmt->execute())
        {
            die($stmt->error);
        }
        $result = $stmt->get_result();
        $row = $result->fetch_object('reponse');
        $stmt->close();
        return $row;
    }

    public static function insererReponse($mysqli, $id_question, $intitule)
    {

        $query = "INSERT INTO `reponse` (`id_question`, `intitule`) VALUES(?,?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param($id_question, $intitule);
        if (!$stmt->execute())
        {
            die($stmt->error);
        }
    }

    public static function getReponses($mysqli, $idQuest)
    {
        $a = array();

        $stmt = $mysqli->prepare("SELECT * FROM reponse AS rep WHERE rep.id_question = ?;");
        $stmt->bind_param('i', $idQuest);

        if (!$stmt->execute())
            die($stmt->error);

        $result = $stmt->get_result();
        
        if (!$result)
            die($mysqli->error);

        while(($row = $result->fetch_object('reponse')))
            $a[] = $row;

        return $a;
    }

// Vérifie que la réponse n°nr est bien associée à la question n°nq
    public static function repIsValid($mysqli, $nq, $nr)
    {
// J'ai déjà vérifié avant chaque appel que $nq et $nr sont des entiers avec ctype !!!
        $query = "SELECT * FROM reponse AS rep WHERE rep.id='$nr' AND rep.id_question='$nq';";

        $result = $mysqli->query($query);

        if (!$result)
            die($mysqli->error);

        return ($result->num_rows != 0);
    }

    public static function traiteChoixForm($mysqli,$idQuestion, $nQuestion,$qcmrep)
    { // Traite tous les choix en rapport à la question d'id ID dont on aura préalablement vérifié qu'elle admet des choix
        $nChoix = count($qcmrep[$nQuestion]); // Nombre de Choix pour la question nQuestion
        for ($j = 0; $j < $nChoix; $j++)
        { // Traitons le choix j pour la question nQuest
            reponse::insererReponse($mysqli, $idQuestion, $qcmrep[$nQuestion][$j]);
        }
    }

}
