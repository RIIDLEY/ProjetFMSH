<?php

class Controller_home extends Controller{

  public function action_default(){
      $this->render('home');
  }

  public function action_upload(){

     if(!empty($_FILES['fichier']) && $_POST['type'] != "-") {
       $tmp_nom = $_FILES['fichier']['tmp_name'];
       if(move_uploaded_file($tmp_nom, 'src/Upload/'.stripAccents(str_replace(' ', '', $_FILES['fichier']['name'])))){//ajoute le fichier à dossier de stockage du serveur avec la suppression des espaces
         if ($_POST['type'] == "Média") {

           $command = escapeshellcmd("python Script/SpeechToText.py src/Upload/".stripAccents(str_replace(' ', '', $_FILES['fichier']['name'])));
           $output = shell_exec($command);
           $output = trim($output);


           $DataFile = file("src/MediaToText/".$output);
            $tmp = array();

             foreach($DataFile as $name)
             {
                 echo utf8_encode($name).'<br>';
             }
         } elseif ($_POST['type'] == "Document") {

         }
       }

     }else{
       echo "<script>alert(\"Il manque des informations\")</script>";
       $this->render('home');
     }

/*
    if(!empty($_FILES['fichier']))
    {

      $tmp_nom = $_FILES['fichier']['tmp_name'];
      move_uploaded_file($tmp_nom, 'Upload/'.$_FILES['fichier']['name']);//ajoute le fichier à dossier de stockage du serveur

      $command = escapeshellcmd("python Script/SpeechToText.py Upload/".$_FILES['fichier']['name']);
      $output = shell_exec($command);
      $output = trim($output);
      $filename = "Script/".$output;


      $this->render('home');

    }else{

    }*/

  }
}

?>
