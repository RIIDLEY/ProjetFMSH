<?php

class Controller_register extends Controller{

  public function action_default(){
        $m = Model::getModel();
        $this->render("register");
  }

  public function action_add(){
    $m = Model::getModel();
    //vérifie si les données sont présentes et si c'est bien des caractères
    if(isset($_POST['login']) and preg_match("#^\S*$#",$_POST['login']) and isset($_POST['mdp']) and preg_match("#^\S*$#",$_POST['mdp']) and isset($_POST['mdpconf']) and preg_match("#^\S*$#",$_POST['mdpconf'])){
     
      $nbLogin = $m->getNbLogin();//get le nombre d'utilisateur
      $listLogin = $m->getLogin();//get le login de tout les utilisateurs

      for($i=0;$i<$nbLogin[0];$i++){//verifie si le login est disponible
        if($_POST['login']===$listLogin[$i]){
          echo '<script type="text/javascript"> alert("Pseudo déjà utilisé"); </script>';
          $this->render("register");
        }
      }
    
      if($_POST['mdp']===$_POST['mdpconf']){//verifie si les 2 mots de passe sont identique
      $info = ['login'=>$_POST['login'],'mdp'=>password_hash($_POST['mdp'],PASSWORD_DEFAULT)];
      $m->addLogin($info);//ajoute dans la BDD
      echo '<script type="text/javascript"> alert("Compte enregistré"); </script>';
      echo("<script>window.location = 'index.php';</script>");
     }
     else{//si les mots de passe ne sont pas identique
      echo '<script type="text/javascript"> alert("Mot de passe non identique"); </script>';
      $this->render("register");//renvoie sur la page de création de compte
     }

    }else{//s'il manque des champs
      echo '<script type="text/javascript"> alert("Champs manquant"); </script>';
      $this->render("register");
    }
  }

}

?>
