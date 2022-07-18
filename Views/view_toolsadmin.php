<?php
require('view_begin.php');
if (session_status() != 2){
    session_start();
}

?>

<?php
if (isset($_SESSION['admin'])) {//Si la variable existe
    if ($_SESSION['admin'] === true) {?>


        <a href="?controller=home">Accueil</a>
        <a href="?controller=upload">upload</a>
        <a href="?controller=login&action=deco">Deco</a>


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
            <button onclick="location.href='?controller=register&action=home'" type="button">Page de création de compte</button>
            <button onclick="location.href='?controller=home&action=home'" type="button">Page d'accueil</button>
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
        <button onclick="location.href='?controller=register&action=home'" type="button">Page de création de compte</button>
        <button onclick="location.href='?controller=home&action=home'" type="button">Page d'accueil</button>
    </center>

    <?php
}?>


<?php
require('view_end.php');
?>