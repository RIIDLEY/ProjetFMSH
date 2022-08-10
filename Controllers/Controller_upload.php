<?php
include "vendor/autoload.php";
use Spatie\PdfToText\Pdf;
class Controller_upload extends Controller{

  public function action_default(){
      $this->render('upload');
  }


    public function action_test(){
        print_r($_POST["Tags"]);
    }

  public function action_upload(){

     if(!empty($_FILES['fichier']) && $_POST['type'] != "-") {
       $tmp_nom = $_FILES['fichier']['tmp_name'];

         $idrandom = str_replace(".","",uniqid('', true));
       if(move_uploaded_file($tmp_nom, 'src/Upload/'.$idrandom."_".stripAccents(str_replace(' ', '', $_FILES['fichier']['name'])))){//ajoute le fichier à dossier de stockage du serveur avec la suppression des espaces

           if ($_POST['type'] == "Media") {//si c'est un fichier audio ou vidéo

           $command = escapeshellcmd("python Script/SpeechToText.py src/Upload/".$idrandom."_".stripAccents(str_replace(' ', '', $_FILES['fichier']['name'])));//fait la transcription
           $output = shell_exec($command);
           $output = trim($output);

           if($output==="Error"){
               echo "<script>alert(\"Une erreure c'est produite lors de la transcription.\")</script>";
               $this->render('upload');
           }else{
               $m = Model::getModel();
               $tmp_infos = ['name'=>$_POST["Name"], 'description' =>$_POST["Description"],'tags'=>$_POST["Tags"],'filename'=>$idrandom."_".stripAccents(str_replace(' ', '', $_FILES['fichier']['name'])), 'transcriptFile'=>$output,'type'=>pathinfo($_FILES['fichier']['name'])['extension'], 'size'=>$_FILES['fichier']['size']];

               $IdDocu = $m->addDoc($tmp_infos);//ajoute le fichier à la BDD

               $this->indexation("src/MediaToText/".$output,$IdDocu,"Media",false);//fait l'indexation

               $this->PageInfo($IdDocu);//affiche la page d'info
           }

         } elseif ($_POST['type'] == "Document") {//si c'est un document numérique
               $m = Model::getModel();
               $filename = $idrandom."_".stripAccents(str_replace(' ', '', $_FILES['fichier']['name']));
               $tmp_infos = ['name'=>$_POST["Name"], 'description' =>$_POST["Description"],'tags'=>$_POST["Tags"],'filename'=>$filename, 'transcriptFile'=>"None",'type'=>pathinfo($_FILES['fichier']['name'])['extension'], 'size'=>$_FILES['fichier']['size']];

               $IdDocu = $m->addDoc($tmp_infos);//ajoute le fichier à la BDD

               $extension = pathinfo("src/Upload/".$filename, PATHINFO_EXTENSION);
               $this->indexation("src/Upload/".$filename,$IdDocu,"Document",$extension);//fait l'indexation

               $this->PageInfo($IdDocu);//affiche la page d'info

         }
       }else{
           echo "<script>alert(\"Une erreure c'est produite lors de l'upload\")</script>";
           $this->render('upload');
       }

     }else{
       echo "<script>alert(\"Il manque des informations\")</script>";
       $this->render('upload');
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

    public function indexation($document, $IDDoc,$type,$PDF){
        $m = Model::getModel();
        if ($type == "Media"){
            $texte = utf8_encode(file_get_contents($document));//lecture du fichier
        }elseif ($PDF==="pdf"){
            //https://stackoverflow.com/questions/67584969/spatie-pdftotext-cant-find-path-of-library-windows/67587222#67587222
            $texte = remove_emoji(stripAccents(utf8_encode((new Pdf("Script/pdftotext.exe"))->setPdf($document)->text())));
        }else{
            $texte = file_get_contents($document);//lecture du fichier
        }

        $separateurs =  "’'. ,-…][(«»)/\r\n|\n|\r/" ;//caracteres de séparation des mots

        $tab_toks = $this->explode_bis(mb_strtolower(stripAccents($texte),"UTF-8"), $separateurs);//séparation

            $command = escapeshellcmd("python Script/lemma.py ".implode(" ", $tab_toks));//fait la lemmatisation
            $output = shell_exec($command);
            $tabLemma = explode("|", $output);
            array_pop($tabLemma);
            $tabLemma = array_map("utf8_encode", $tabLemma );

        $tab_new_mots_occurrences = array_count_values ($tabLemma);//compte le nombre d'occurence

        foreach($tab_new_mots_occurrences as $k=> $v){//Boucle qui tourne dans le tableau $tab_new_mots_occurrences qui contient le mot avec son occurence et le document dont il provient
            $infos = array("word"=>$k,"occurence"=>$v,"fileID"=>$IDDoc);
            $m->addMot($infos);//ajoute dans la BDD
        }

    }

    public function PageInfo($IdFile)
    {
        $m = Model::getModel();
        $infoFile = $m->getDocByID($IdFile);//get les infos du doc via son ID

        $pathFile = "src/Upload/".$infoFile["Filename"];
        $extension = pathinfo($pathFile, PATHINFO_EXTENSION);
        if ($infoFile["Type"] != "pdf" and $infoFile["Type"] != "txt"){//si c'est un fichier audio/vidéo
            $TranscriptFile = "src/MediaToText/".$infoFile["TranscriptFile"];//get son fichier de transcription
        }else{
            $TranscriptFile = "None";
        }

        $arrayKeyWord = $m->getMot($IdFile);//Recupere la liste de mots clés du document courant

        $DocSimi = $m->CloudDocumentSimilaire($arrayKeyWord);//recupere la liste des documents similaires

        $this->render('cloud',['Name'=>$infoFile["Name"],'tabWord'=>$arrayKeyWord,'PathFile'=>$pathFile,'Description'=>$infoFile["Description"],'Tags'=>$infoFile["Tags"],'TranscriptFile'=>$TranscriptFile,'Extension'=>$extension, "ListeDocuSim"=>$DocSimi]);
    }
}
?>
