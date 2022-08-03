<?php
session_start();
class Controller_home extends Controller{

    public function action_default(){
        $this->render('home');
    }


    public function action_recherche(){//cherche un mot clé
        $m = Model::getModel();
        if(isset($_POST['KeyWords']) and !preg_match("#^\s*$#",$_POST['KeyWords'])){
            $splitKeyWords = explode(" ", $_POST['KeyWords']);

            $command = escapeshellcmd("python Script/lemma.py ".$_POST['KeyWords']);
            $output = shell_exec($command);
            $tabLemma = explode("|", $output);
            array_pop($tabLemma);
            $tabLemma = array_map("utf8_encode", $tabLemma );
            $splitKeyWordsLower = array_map('strtolower', $splitKeyWords);


            $ListFiles = $m->getDocumentbyMotV2($tabLemma);
            $ListKeyWords = array();
            foreach ($ListFiles as $value) {
                 //print_r($value);
                $ListKeyWords = array_merge($ListKeyWords,array($m->getListMotsByFilID($value)));
            }

            //$ListKeyWords = $m->getListMotsByFilID($m->getDocumentbyMotV2($tabLemma)[0]);



            $this->render('home', ['ListFiles'=>$ListFiles,'ListeKeyWords'=>$ListKeyWords]);//envoie les données à la page
        }else{
            $this->render("home");
        }

    }

}

?>
