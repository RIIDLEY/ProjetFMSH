<?php
require('view_begin.php');
include 'Utils/import_sigmaJS.php';

?>
    <div class="container">
        <div class="row">
            <div class="col grandeDivData">
                <form class="form-inline" action = "?controller=home&action=recherche" method="post" style="display: inline-block;">
                    <input type="text" name="KeyWords" size="50" placeholder="Mot clés"/>
                    <input type="submit" value="Chercher" class="btn btn-primary mb-2"/></form>
            </div>
        </div>
    <div class="row">
        <div class="divStyle col-md-auto">
        <?php
        if (isset($liste)){
        foreach ($liste as $key => $value){  ?>
            <li class="list-group-item">Document : <strong><?=$value?></strong></li>
        <?php
        }?>
            <script>
                var my_javascript_variable = <?php echo json_encode($liste) ?>;
            </script>
        <?php } ?>
    </div>
        <div class="col">
            <div id='sigma-container'></div>
        </div>
    </div>
    </div>

<?php
if (isset($_SESSION['admin'])) {//Si la variable existe
    if ($_SESSION['admin'] === true) {//si c'est un admin
        echo '<a href="?controller=toolsadmin">Page Admin</a>';//ajoute le bouton de deconnection
    }else{
        echo '<a href="?controller=toolsadmin">Connection admin</a>';
    }
}else{
    echo '<a href="?controller=toolsadmin">Connection admin</a>';
}
?>


<?php
if (isset($liste)){?>
    <script type="text/javascript" src="Script/GraphReseau.js"></script>

<?php
}
require('view_end.php');
?>