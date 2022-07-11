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
            return $this->lastinsert(["name"=>$infos["name"],"size"=>$infos["size"],"filename"=>$infos["filename"],"tags"=>$infos["tags"]])[0];
        } catch (PDOException $e) {
            die('Echec addDoc, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }

    public function getDocByID($fileID)
    {

        try {
            $requete = $this->bd->prepare('Select Name from fichiers_upload WHERE FileID = :fileID');
            $requete->bindValue(':fileID', $fileID);
            $requete->execute();
            return $requete->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Echec getDocByID, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }

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

    public function getMot($fileID)
    {

        try {
            $requete = $this->bd->prepare('Select Word, Occurence from indexation WHERE FileID = :fileID');
            $requete->bindValue(':fileID', $fileID);
            $requete->execute();
            return $requete->fetchall(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Echec getMot, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }

    public function lastinsert($infos)
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
            die('Echec lastinsert, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }

    public function getListMot($mot)
    {

        try {
            $requete = $this->bd->prepare('Select * from indexation WHERE Word = :word ORDER BY Occurence DESC');
            $requete->bindValue(':word', $mot);
            $requete->execute();
            return $requete->fetchall(PDO::FETCH_NUM);
        } catch (PDOException $e) {
            die('Echec getListMot, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }

    public function getDocumentbyMot($mot)
    {

        try {
            $requete = $this->bd->prepare('Select FileID from indexation WHERE Word = :word ORDER BY Occurence DESC');
            $requete->bindValue(':word', $mot);
            $requete->execute();
            return $requete->fetchall(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Echec getDocumentbyMot, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }

    public function getDocumentbyMotV2($ArrayWord)
    {

        try {

            //$sql = "SELECT FileID FROM `indexation` WHERE Word IN ('problèmes', 'femmes') GROUP BY FileID HAVING COUNT(*) = 2";

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


}
