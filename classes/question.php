<?php
// Sert à implémenter la classe question reliée à la base de donnée ainsi que toutes les fonctions utilisant la classe
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
        $stmt = $mysqli->prepare("INSERT INTO `question` (`intitule`, `type`, `id_shotgun`) VALUES(?,?,?)");

        if(!$stmt || !($stmt->bind_param('sii', $intitule, $type, $id_shotgun)) || !($stmt->execute()))
            die("Erreur fatale dans insererQuestion");

        $idQuestion = $stmt->insert_id;
        $stmt->close();

        return $idQuestion;
    }

    // Récupère la question à partir de son id
    public static function getQuestionFromId($mysqli, $id)
    {
        $stmt = $mysqli->prepare("SELECT * FROM question WHERE id = ?");

        if(!$stmt || !($stmt->bind_param('i', $id)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die("Erreur fatale dans getQuestionFromId");

        $row = $result->fetch_object('question');

        $stmt->close();
        return $row;
    }

    // Récupère les questions du shotgun $idShot
    public static function getQuestions($mysqli, $idShot)
    {
        $a = array();

        $stmt = $mysqli->prepare("SELECT * FROM question AS quest WHERE quest.id_shotgun = ? ORDER BY quest.id ASC;");

        if(!$stmt || !($stmt->bind_param('i', $idShot)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die("Erreur fatale dans getQuestions");

        while(($row = $result->fetch_object('question')))
            $a[] = $row;

        $stmt->close();
        return $a;
    }

    // Donne la question associée à la réponse id
    public static function getQuestion_Rep($mysqli, $id)
    {
        $stmt = $mysqli->prepare("SELECT question.* FROM question,reponse WHERE reponse.id = ? AND question.id = reponse.id_question");
        
        if(!$stmt || !($stmt->bind_param('i', $id)) || !($stmt->execute()) || !($result = $stmt->get_result()))
            die("Erreur fatale dans getQuestion_Rep");
    
        $row = $result->fetch_object('question');
        $stmt->close();
    
        return $row;
    }

    // Insere la question i du formulaire dans la BDD
    public static function traiteQuestionForm($mysqli, $intitule, $typeReponse, $id_shotgun, $i)
    {
        return question::insererQuestion($mysqli, $intitule[$i], $typeReponse[$i], $id_shotgun);
    }

}