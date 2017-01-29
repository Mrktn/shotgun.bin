<?php

class reponse // Réponse = Choix 
{

    public $id;
    public $id_question;
    public $intitule;

    public static function getReponseFromId($mysqli, $id)
    {
        $stmt = $mysqli->prepare("SELECT * FROM reponse WHERE id = ?");

        if (!$stmt || !($stmt->bind_param('i', $id)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die("Erreur irrécupérable dans getReponseFromId");

        $row = $result->fetch_object('reponse');
        $stmt->close();
        return $row;
    }

    public static function insererReponse($mysqli, $id_question, $intitule)
    {
        $stmt = $mysqli->prepare("INSERT INTO `reponse` (`id_question`, `intitule`) VALUES(?,?)");

        if (!$stmt || !($stmt->bind_param('is', $id_question, $intitule)) || !($stmt->execute()))
            die("Erreur irrécupérable dans insererReponse");

        $stmt->close();
        return true;
    }

    public static function getReponses($mysqli, $idQuest)
    {
        $a = array();

        $stmt = $mysqli->prepare("SELECT * FROM reponse AS rep WHERE rep.id_question = ?;");

        if (!$stmt || !($stmt->bind_param('i', $idQuest)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die('Erreur irrécupérable dans getReponses');

        while (($row = $result->fetch_object('reponse')))
            $a[] = $row;

        $stmt->close();
        return $a;
    }

    // Vérifie que la réponse n°nr est bien associée à la question n°nq
    public static function repIsValid($mysqli, $nq, $nr)
    {
        $stmt = $mysqli->prepare("SELECT * FROM reponse AS rep WHERE rep.id = ? AND rep.id_question = ?");

        if (!$stmt || !($stmt->bind_param('ii', $nr, $nq)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die('Erreur irrécupérable dans repIsValid');

        $stmt->close();
        return ($result->num_rows != 0);
    }

    // Traite tous les choix en rapport à la question d'id ID dont on aura préalablement vérifié qu'elle admet des choix
    public static function traiteChoixForm($mysqli, $idQuestion, $nQuestion, $qcmrep)
    {
        $failed = false;
        $nChoix = count($qcmrep[$nQuestion]); // Nombre de Choix pour la question nQuestion
        // Traitons le choix j pour la question nQuest
        for ($j = 0; ($j < $nChoix) && !$failed; $j++)
        {
            if ($qcmrep[$nQuestion][$j] != "")
            {
                $failed = $failed || !reponse::insererReponse($mysqli, $idQuestion, $qcmrep[$nQuestion][$j]);
            }
        }

        return !$failed;
    }

    public static function insertChoixLibre($mysqli, $idQuestion)
    {
        return reponse::insererReponse($mysqli, $idQuestion, "");
    }

}
