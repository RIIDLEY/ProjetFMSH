<?php
require('view_begin.php');
include 'Utils/import_sigmaJS.php';

?>


    <form class="form-inline" action = "?controller=home&action=recherche" method="post" style="display: inline-block;">
        <input type="text" name="KeyWords" size="50" placeholder="Mot clés"/>
        <input type="submit" value="Chercher" class="btn btn-primary mb-2"/></form>
    <ul>
        <div id='sigma-container'></div>

        <?php
        if (isset($liste)){
        foreach ($liste as $key => $value){  ?>
            <li class="list-group-item">Document : <strong><?=$value["Name"]?></strong></li>
        <?php
        }?>
            <script>

                var my_javascript_variable = <?php echo json_encode($liste) ?>;
                console.log(my_javascript_variable[0]);
            </script>
            <script type="text/javascript" src="Script/GraphReseau.js"></script>
        <?php } ?></ul>



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