<?php

class Controller_home extends Controller{

    public function action_default(){
        $this->render('home');
    }


    public function action_recherche(){//cherche un mot clé
        $m = Model::getModel();
        $tabsend = array();
        if(isset($_POST['KeyWords']) and !preg_match("#^\s*$#",$_POST['KeyWords'])){
            $splitKeyWords = explode(" ", $_POST['KeyWords']);
            //Lemmatisation à faire
            $splitKeyWordsLower = array_map('strtolower', $splitKeyWords);
        }

        $this->render('home', ['liste'=>$m->getDocumentbyMotV2($splitKeyWordsLower)]);//envoie les données à la page
    }

}

?>
