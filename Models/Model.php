<?php

class Model
{


    /**
     * Attribut contenant l'instance PDO
     */
    private $bd;


    /**
     * Attribut statique qui contiendra l'unique instance de Model
     */
    private static $instance = null;


    /**
     * Constructeur : effectue la connexion à la base de données.
     */
    private function __construct()
    {

        try {
            include 'Utils/credentials.php';
            $this->bd = new PDO($dsn, $login, $mdp);
            $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->bd->query("SET nameS 'utf8'");
        } catch (PDOException $e) {
            die('Echec connexion, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }


    /**
     * Méthode permettant de récupérer un modèle car le constructeur est privé (Implémentation du Design Pattern Singleton)
     */
    public static function getModel()
    {

        if (is_null(self::$instance)) {
            self::$instance = new Model();
        }
        return self::$instance;
    }

    /**
     * Méthode permettant de reference un document dans le DBB
     */

    public function addDoc($infos)
    {

        try {
            $requete = $this->bd->prepare('INSERT INTO fichiers_upload (Name,Description,Tags,Filename,TranscriptFile,Type,Size) VALUES (:name,:description,:tags,:filename,:transcriptFile,:type,:size);  SELECT LAST_INSERT_ID();');
            $marqueurs = ['name', 'description','tags', 'filename', 'transcriptFile','type', 'size'];
            foreach ($marqueurs as $value) {
                $requete->bindValue(':' . $value, $infos[$value]);
            }
            $requete->execute();
            $requete->closeCursor();
            return $this->IDlastinsert(["name"=>$infos["name"],"size"=>$infos["size"],"filename"=>$infos["filename"],"tags"=>$infos["tags"]])[0];
        } catch (PDOException $e) {
            die('Echec addDoc, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }


    /**
     * Méthode permettant de recuperer les informations d'un document avec son ID
     */
    public function getDocByID($fileID)
    {

        try {
            $requete = $this->bd->prepare('Select * from fichiers_upload WHERE FileID = :fileID');
            $requete->bindValue(':fileID', $fileID);
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_ASSOC)[0];
        } catch (PDOException $e) {
            die('Echec getDocByID, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }

    /**
     * Méthode permettant d'ajouter des mots clés à la BDD
     */
    public function addMot($infos)
    {
        try {
            $requete = $this->bd->prepare('INSERT INTO indexation (Word, Occurence, FileID) VALUES (:word, :occurence, :fileID);');
            $marqueurs = ['word', 'occurence', 'fileID'];
            foreach ($marqueurs as $value) {
                $requete->bindValue(':' . $value, $infos[$value]);
            }
            return $requete->execute();
        } catch (PDOException $e) {
            die('Echec addMot, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }

    /**
     * Méthode permettant de recuperer les mots clés d'un document
     */

    public function getMot($fileID)
    {

        try {
            $requete = $this->bd->prepare('Select Word, Occurence from indexation WHERE FileID = :fileID ORDER BY Occurence DESC LIMIT 10');
            $requete->bindValue(':fileID', $fileID);
            $requete->execute();
            return $requete->fetchall(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Echec getMot, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }

    /**
     * Méthode permettant de recuperer l'ID du dernier document inseré
     */

    public function IDlastinsert($infos)
    {
        try {
            $requete = $this->bd->prepare('SELECT FileID FROM fichiers_upload WHERE Name=:name AND Size=:size AND Filename=:filename AND Tags=:tags');
            $marqueurs = ['name','size', 'filename','tags'];
            foreach ($marqueurs as $value) {
                $requete->bindValue(':' . $value, $infos[$value]);
            }
            $requete->execute();
            return $requete->fetchall(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            die('Echec IDlastinsert, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }

    /**
     * Méthode permettant de recuperer l'ID du dernier document inseré
     */

    public function getListMotsByFilID($InfoFile)
    {
        $InfoFileArray = array("FileID"=>$InfoFile["FileID"],"FileName"=>$InfoFile["Name"]);
        try {
            $requete = $this->bd->prepare('Select Word from indexation WHERE FileID = :FileID ORDER BY Occurence DESC LIMIT 5');
            $requete->bindValue(':FileID', $InfoFile["FileID"]);
            $requete->execute();
            //return $requete->fetchall(PDO::FETCH_NUM);
            return array_merge($InfoFileArray,array("ListKeyWords"=>$requete->fetchall(PDO::FETCH_NUM)));
        } catch (PDOException $e) {
            die('Echec getListMotsByFilID, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }

    /**
     * Méthode permettant de recuperer les documents par rapport au nuage de mot cle courant
     */

    public function CloudDocumentSimilaire($ArrayWord){

        try {

            $arrayDocuID = array();
            $arrayDocuName = array();

            do{
                $sql = "SELECT FileID FROM indexation WHERE Word IN (";

                for ($i = 0;$i<count($ArrayWord);$i++){
                    $sql .= "'".$ArrayWord[$i]["Word"]."'";
                    if ($i!=count($ArrayWord)-1){
                        $sql .= ",";
                    }
                }
                $sql .= ") GROUP BY FileID DESC HAVING COUNT(*) = ".count($ArrayWord)." LIMIT 6";

                $requete = $this->bd->prepare($sql);
                $requete->execute();
                $arrayReturnRequete = $requete->fetchall(PDO::FETCH_ASSOC);

                foreach ($arrayReturnRequete as $value){
                    if (!in_array($value,$arrayDocuID)){
                        array_push($arrayDocuID,$value);
                    }
                }
                array_pop($ArrayWord);
            }while(count($ArrayWord)>=1);

            foreach ($arrayDocuID as $value){
                array_push($arrayDocuName,$this->getDocByID($value["FileID"]));
            }

            $shift = array_shift($arrayDocuName);
            return $arrayDocuName;
        } catch (PDOException $e) {
            die('Echec getDocumentbyMot, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }

    /**
     * Méthode permettant de recuperer les documents par a la list de mot clé courante
     */

    public function getDocumentbyMotV2($ArrayWord)
    {

        try {

            $arrayDocuID = array();
            $arrayDocuName = array();

            do{
                $sql = "SELECT FileID FROM indexation WHERE Word IN (";

                for ($i = 0;$i<count($ArrayWord);$i++){
                    $sql .= "'".$ArrayWord[$i]."'";
                    if ($i!=count($ArrayWord)-1){
                        $sql .= ",";
                    }
                }
                $sql .= ") GROUP BY FileID HAVING COUNT(*) = ".count($ArrayWord);

                $requete = $this->bd->prepare($sql);
                $requete->execute();
                $arrayReturnRequete = $requete->fetchall(PDO::FETCH_ASSOC);

                foreach ($arrayReturnRequete as $value){
                    if (!in_array($value,$arrayDocuID)){
                        array_push($arrayDocuID,$value);
                    }
                }
                array_pop($ArrayWord);
            }while(count($ArrayWord)>=1);

            foreach ($arrayDocuID as $value){
                array_push($arrayDocuName,$this->getDocByID($value["FileID"]));
            }


            return $arrayDocuName;

        } catch (PDOException $e) {
            die('Echec getDocumentbyMotV2, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }





/**
Fonctions gestion compte
 **/

    public static function addLogin($infos)
    {
        $m = Model::getModel();
        try {
            //Préparation de la requête
            $requete = $m->bd->prepare('INSERT INTO admin (Name, Password) VALUES (:login, :mdp)');
            //Remplacement des marqueurs de place par les valeurs
            $marqueurs = ['login', 'mdp'];
            foreach ($marqueurs as $value) {
                $requete->bindValue(':' . $value, $infos[$value]);
            }
            //Exécution de la requête
            return $requete->execute();
        } catch (PDOException $e) {
            die('Echec addLogin, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }


    /**
     * Méthode permettant d'obtenir le nom des utilisateurs
     */
    public function getLogin()
    {

        try {
            $requete = $this->bd->prepare('SELECT Name FROM admin');
            $requete->execute();
            $reponse = [];
            while ($ligne = $requete->fetch(PDO::FETCH_ASSOC)) {
                $reponse[] = $ligne['Name'];
            }
            return $reponse;
        } catch (PDOException $e) {
            die('Echec getLogin, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }

    /**
     * Méthode permettant d'obtenir le nombre d'utilisateur
     */
    public function getNbLogin()
    {
        try {
            $requete = $this->bd->prepare('SELECT count(*) FROM admin');
            $requete->execute();
            return $requete->fetch(PDO::FETCH_NUM);
        } catch (PDOException $e) {
            die('Echec getNbLogin, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }


    /**
     * Méthode permettant d'obtenir le mot de passe de l'utilisateur hashé
     */
    public function getMDP($user)
    {
        try {
            $requete = $this->bd->prepare('SELECT Password FROM admin WHERE Name = :user');
            $requete->bindValue(':user', $user);
            $requete->execute();
            return $requete->fetch(PDO::FETCH_NUM);
        } catch (PDOException $e) {
            die('Echec getMDP, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }


}
