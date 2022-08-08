<?php
require('view_begin.php');
if (session_status() != 2){
    session_start();
}

?>
    <script>
        var element = document.getElementById("admin");//Modifie la navbar en fonction de la page actuel
        element.classList.add("active");

    </script>
<?php
if (isset($_SESSION['admin'])) {//Si la variable existe
    if ($_SESSION['admin'] === true) {?>

        <div class="container">
            <div class="row">
                <div class="col buuttonAdmin">
                    <a href="?controller=upload" style="text-decoration:none;color: inherit;">Outils d'Upload</a>
                </div>
                <div class="col buuttonAdmin">
                    <a href="?controller=login&action=deco" style="text-decoration:none;color: inherit;">Déconnexion</a>
                </div>

            </div>
        </div>




        <?php
    }else{?>

        <center>
            <h1>Connexion</h1>

            <form action = "?controller=login&action=login" method="post">
                <p> <label> Identifiant: <input type="text" name="login" required/> </label> </p>
                <p> <label> Mot de passe: <input type="password" name="mdp" required/> </label></p>
                <input type="submit" value="Se connecter"/>
                <input type="reset" value="Reset">
            </form>
            <br>
            <!--<button onclick="location.href='?controller=register&action=home'" type="button">Page de création de compte</button>-->
        </center>


        <?php
    }
}else{?>
    <center>
        <h1>Connexion</h1>

        <form action = "?controller=login&action=login" method="post">
            <p> <label> Identifiant: <input type="text" name="login" required/> </label> </p>
            <p> <label> Mot de passe: <input type="password" name="mdp" required/> </label></p>
            <input type="submit" value="Se connecter"/>
            <input type="reset" value="Reset">
        </form>
        <br>
        <!--<button onclick="location.href='?controller=register&action=home'" type="button">Page de création de compte</button>-->
    </center>

    <?php
}?>


<?php
require('view_end.php');
?>