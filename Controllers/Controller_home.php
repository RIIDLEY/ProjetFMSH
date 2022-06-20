<?php

class Controller_home extends Controller{

  public function action_default(){
      $this->render('home');
  }

  public function action_upload(){

    if(!empty($_FILES['fichier']))
    {

      $tmp_nom = $_FILES['fichier']['tmp_name'];
      move_uploaded_file($tmp_nom, 'Upload/'.$_FILES['fichier']['name']);//ajoute le fichier à dossier de stockage du serveur

      $command = escapeshellcmd("python Script/SpeechToText.py Upload/".$_FILES['fichier']['name']);
      $output = shell_exec($command);
      $output = trim($output);
      $filename = "Script/".$output;
      echo '<iframe width="1" height="1" frameborder="0" src="'.$filename.'"></iframe>';
      $this->render('home');


    }else{
      echo "<script>alert(\"Erreur\")</script>";
      $this->render('home');
    }

  }
}

?>
