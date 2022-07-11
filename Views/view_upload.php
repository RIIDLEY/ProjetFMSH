<?php
require('view_begin.php');
?>
    <span class="label label-info">Info Label</span>

    <div class="grandeDivData container">
    <u><h1>Formulaire d'upload :</h1></u>
    <form action = "?controller=upload&action=upload" method="post" enctype="multipart/form-data" style="display:inline;">

        <div class="row">
            <div class="col-md-6">
                <input type="text" class="form-control" name="Name" placeholder="Nom du document" required>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" name="Description" placeholder="Description" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label>Types de document : </label>
                <select name="type" class="others">
                    <option class="others">-</option>
                    <option class="others">Média</option>
                    <option class="others">Document</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="Tags" data-role="tagsinput" placeholder="Mots-clés" required/>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <input type="file" class="form-control-file" name="fichier">
            </div>
        </div>
        <input type="submit" class="btn btn-primary btn-lg" value="Envoyer"/>
    </form>


    </div>


<?php
require('view_end.php');
?>