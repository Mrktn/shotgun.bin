<?php
class User {

    public $login;
    public $mdp;
    public $nom;
    public $prenom;
    public $promotion;
    public $naissance;
    public $email;
    public $feuille;

    public function __toString() {
        $mydate = explode('-', $this->naissance);
        return "[$this->login] $this->prenom $this->nom, né le ${mydate[2]}/${mydate[1]}/${mydate[0]}" . ($this->promotion ? ", X" . "$this->promotion, " : "") . "$this->email";        
    }

    public static function getUtilisateur($dbh, $login) {
        $sth = $dbh->prepare("SELECT * FROM `utilisateurs` WHERE login=?");
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
        $sth->execute(array($login));
        $b = $sth->fetch();
        
        if($b)
            return $b;
        else
            return NULL;
    }

    public static function insererUtilisateur($dbh, $login, $mdp, $nom, $prenom, $promotion, $naissance, $email, $feuille) {
        $sth = $dbh->prepare("INSERT INTO `utilisateurs` (`login`, `mdp`, `nom`, `prenom`, `promotion`, `naissance`, `email`, `feuille`) VALUES(?,SHA1(?),?,?,?,?,?,?)");
        $sth->execute(array($login, sha1($mdp), $nom, $prenom, $promotion, $naissance, $email, $feuille));
    }
    
    public static function testerMdp($dbh,$login,$mdp)
    {
        $sth = $dbh->prepare("SELECT * FROM `utilisateurs` WHERE mdp=? AND login=?");
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
        $sth->execute(array(sha1($mdp), $login));
        
        return !!$sth->fetch();
    }
}
?>