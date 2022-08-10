<?php
session_start();
class Controller_home extends Controller{

    public function action_default(){
        $this->render('home');
    }


    public function action_recherche(){//cherche un mot clé
        $m = Model::getModel();
        if(isset($_POST['KeyWords']) and !preg_match("#^\s*$#",$_POST['KeyWords'])){

            $command = escapeshellcmd("python Script/lemma.py ".$_POST['KeyWords']);//fait la lemmatisation sur les mots cles de la recherche
            $output = shell_exec($command);
            $tabLemma = explode("|", $output);
            array_pop($tabLemma);
            $tabLemma = array_map("utf8_encode", $tabLemma );

            $ListFiles = $m->getDocumentbyMotV2($tabLemma);//get les documents par rapport aux mots clés
            $ListKeyWords = array();
            foreach ($ListFiles as $value) {
                $ListKeyWords = array_merge($ListKeyWords,array($m->getListMotsByFilID($value)));
            }

            $this->render('home', ['ListFiles'=>$ListFiles,'ListeKeyWords'=>$ListKeyWords]);//envoie les données à la page
        }else{
            $this->render("home");
        }

    }

}

?>
