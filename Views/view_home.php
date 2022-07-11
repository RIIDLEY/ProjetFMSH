<?php
require('view_begin.php');
?>


    <form class="form-inline" action = "?controller=home&action=recherche" method="post" style="display: inline-block;">
        <input type="text" name="KeyWords" size="50" placeholder="Mot clés"/>
        <input type="submit" value="Chercher" class="btn btn-primary mb-2"/></form>


    <ul>
        <?php
        if (isset($liste)){
            print_r($liste);

        foreach ($liste as $key => $value){  ?>
            <li class="list-group-item">Document : <strong><?=$value["Name"]?></strong></li>
        <?php
        }
        } ?>
    </ul>

    <a href="?controller=upload">upload</a>




<?php
if (isset($_SESSION['admin'])) {//Si la variable existe
    if ($_SESSION['admin'] === true) {//si c'est un admin
        echo '<a href="?controller=home&action=deco">Se deconnecter</a>';//ajoute le bouton de deconnection
    }else{
        echo '<a href="?controller=toolsadmin">Connection admin</a>';
    }
}else{
    echo '<a href="?controller=toolsadmin">Connection admin</a>';
}
?>

<?php
require('view_end.php');
?>