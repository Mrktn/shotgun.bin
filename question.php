<?php

class question
{

    public $id;
    public $intitule;
    public $type;
    public $id_shotgun;
    public static $TYPE_CHOIXMULTIPLE = 0;
    public static $TYPE_CHOIXUNIQUE = 1;
    public static $TYPE_REPONSELIBRE = 2;

    public static function insererQuestion($mysqli, $intitule, $type, $id_shotgun)
    {
        $stmt = $mysqli->prepare("INSERT INTO `question` (`intitule`, `type`, `id_shotgun`) VALUES(?,?,?))");
        $stmt->bind_param('isii', $intitule, $type, $id_shotgun);
        if (!$stmt->execute())
        {
            die($stmt->error);
        }
        $idQuestion = $stmt->insert_id;
        return $idQuestion;
    }

    // Récupère la question à partir de son id
    public static function getQuestionFromId($mysqli, $id)
    {
        $query = "SELECT * FROM question WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $id);
        if (!$stmt->execute())
        {
            die($stmt->error);
        }
        $result = $stmt->get_result();
        $row = $result->fetch_object('question');
        $stmt->close();
        return $row;
    }

    // Récupère les questions du shotgun $idShot
    public static function getQuestions($mysqli, $idShot)
    {
        $a = array();

        // On sélectionne ceux qui ne sont pas ecore publiés à cause de leur date de publi, mais qui sont actifs
        $query = "SELECT * FROM question AS quest WHERE quest.id_shotgun='$idShot' ORDER BY quest.id ASC;";

        $result = $mysqli->query($query);

        if (!$result)
            die($mysqli->error);

        while (($row = $result->fetch_object('question')))
        {
            $a[] = $row;
        }

        return $a;
    }

    public static function getQuestion_Rep($mysqli, $id)
    { // Donne la question associée à la réponse id
        $query = "SELECT question.* FROM question,reponse WHERE reponse.id =? AND question.id = id_question ";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $id);
        if (!$stmt->execute())
        {
            die($stmt->error);
        }
        $result = $stmt->get_result();
        $row = $result->fetch_object('question');
        $stmt->close();
        return $row;
    }

    public static function traiteQuestionForm($mysqli, $intitule, $typeReponse, $id_shotgun, $i)
    { // Insere la question i du formulaire dans la BDD
        return insererQuestion($mysqli, $intitule[$i], $typeReponse[$i], $id_shotgun);
    }

}
