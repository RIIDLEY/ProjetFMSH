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
            $requete = $this->bd->prepare('INSERT INTO fichiers_upload (Name,Description,Filename,TranscriptFile,Type,Size) VALUES (:name,:description,:filename,:transcriptFile,:type,:size);  SELECT LAST_INSERT_ID();');
            $marqueurs = ['name', 'description', 'filename', 'transcriptFile','type', 'size'];
            foreach ($marqueurs as $value) {
                $requete->bindValue(':' . $value, $infos[$value]);
            }
            $requete->execute();
            $requete->closeCursor();
            return $this->lastinsert(["name"=>$infos["name"],"size"=>$infos["size"],"filename"=>$infos["filename"]])[0];
        } catch (PDOException $e) {
            die('Echec addDoc, erreur n°' . $e->getCode() . ':' . $e->getMessage());
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
            $requete = $this->bd->prepare('Select Word, Occurence from indexation WHERE FileID = :fileID ORDER BY Occurence DESC');
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
            $requete = $this->bd->prepare('SELECT FileID FROM fichiers_upload WHERE Name=:name AND Size=:size AND Filename=:filename');
            $marqueurs = ['name','size', 'filename'];
            foreach ($marqueurs as $value) {
                $requete->bindValue(':' . $value, $infos[$value]);
            }
            $requete->execute();
            return $requete->fetchall(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            die('Echec lastinsert, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }



}
