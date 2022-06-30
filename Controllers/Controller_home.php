<?php

class Controller_home extends Controller{

  public function action_default(){
      $this->render('home');
  }


    public function action_test(){
        print_r($_POST["Tags"]);
    }

  public function action_upload(){

     if(!empty($_FILES['fichier']) && $_POST['type'] != "-") {
       $tmp_nom = $_FILES['fichier']['tmp_name'];
         $idrandom = str_replace(".","",uniqid('', true));
       if(move_uploaded_file($tmp_nom, 'src/Upload/'.$idrandom."_".stripAccents(str_replace(' ', '', $_FILES['fichier']['name'])))){//ajoute le fichier à dossier de stockage du serveur avec la suppression des espaces

           if ($_POST['type'] == "Média") {

           $command = escapeshellcmd("python Script/SpeechToText.py src/Upload/".$idrandom."_".stripAccents(str_replace(' ', '', $_FILES['fichier']['name'])));
           $output = shell_exec($command);
           $output = trim($output);

           $m = Model::getModel();
           $tmp_infos = ['name'=>$_POST["Name"], 'description' =>$_POST["Description"],'tags'=>$_POST["Tags"],'filename'=>$idrandom."_".stripAccents(str_replace(' ', '', $_FILES['fichier']['name'])), 'transcriptFile'=>$output,'type'=>pathinfo($_FILES['fichier']['name'])['extension'], 'size'=>$_FILES['fichier']['size']];

           $reponseSQL = $m->addDoc($tmp_infos);

           $this->indexation($output,$reponseSQL);

           echo "<script>alert(\"Done\")</script>";
           $arraytmp = $m->getMot($reponseSQL);
           $this->render('cloud',['tab'=>$arraytmp]);

         } elseif ($_POST['type'] == "Document") {

         }
       }else{
           echo "<script>alert(\"Une erreure c'est produite lors de l'upload\")</script>";
           $this->render('home');
       }

     }else{
       echo "<script>alert(\"Il manque des informations\")</script>";
       $this->render('home');
     }

  }





  //----------------------

    public function explode_bis($texte, $separateurs){
        $tok =  strtok($texte, $separateurs);//separe la chaine en tableau par rapport aux separateurs
        $listemotvide = file_get_contents ("Utils/motsvides.txt");//Sort le fichier de mot vide
        $separateurs2 =  "\n" ;
        $motvide = explode($separateurs2,$listemotvide);//met le fichier de mot vide sous forme de tableau
        $tab_tok=array();

        for ($i=0; $i < count($motvide); $i++) {//enleve les espaces present au tour des mots du fichier de mot vide
            $motvide[$i] = trim($motvide[$i]);
        }

        if(strlen($tok) > 2  && !in_array($tok,$motvide))$tab_tok[] = $tok;//Si la taille du mot est supérieur à 2 et qu'il est pas present dans le tableau de mot vide on le garde

        while ($tok !== false) {
            $tok = strtok($separateurs);
            if(strlen($tok) > 2  && !in_array($tok,$motvide))$tab_tok[] = $tok;//Si la taille du mot est supérieur à 2 et qu'il est pas present dans le tableau de mot vide on le garde
        }

        return $tab_tok;
    }

    public function indexation($document, $IDDoc){
        $m = Model::getModel();
        $chemin = "src/MediaToText/" . $document;//generation du chemin
        $texte = utf8_encode(file_get_contents($chemin));//lecture du fichier

        $separateurs =  "’'. ,-…][(«»)/\r\n|\n|\r/" ;//caracteres de séparation des mots

        $tab_toks = $this->explode_bis(mb_strtolower($texte), $separateurs);//séparation
        //print_r($tab_toks);
        $tab_new_mots_occurrences = array_count_values ($tab_toks);//compte le nombre d'occurence

        foreach($tab_new_mots_occurrences as $k=> $v){//Boucle qui tourne dans le tableau $tab_new_mots_occurrences qui contient le mot avec son occurence et le document dont il provient
            $infos = array("word"=>$k,"occurence"=>$v,"fileID"=>$IDDoc);
            $m->addMot($infos);//ajoute dans la BDD
        }

    }
}

?>
