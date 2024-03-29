<?php
require('view_begin.php');
session_start();
if (isset($_SESSION['admin'])) {//Si la variable existe
    if ($_SESSION['admin'] === true) {
?>
        <script type="text/javascript" src="src/js/Upload.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <div class="grandeDivData container">
    <u><h1>Formulaire d'upload :</h1></u>
    <form action = "?controller=upload&action=upload" method="post" enctype="multipart/form-data" style="display:inline;">

        <div class="row" style="padding: 2%">
            <div class="col-md-6">
                <input type="text" class="form-control" name="Name" placeholder="Nom du document" maxLength="50" required>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" name="Description" placeholder="Description" maxLength="120" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label>Types de document : </label>
                <select name="type" class="others" id="selectid" onChange = "func()" required>
                    <option value="">-</option>
                    <option value="Media">Média</option>
                    <option value="Document">Document</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="Tags" data-role="tagsinput" placeholder="Mots-clés" required/>
            </div>
        </div>

        <div class="row" style="padding: 2%">
            <div class="col-md-6">
                <input id="InputFile" type="file" class="form-control-file" name="fichier" accept=".txt,.pdf">
            </div>
        </div>
        <input type="submit" class="btn btn-primary btn-lg" value="Envoyer"/>
    </form>


        <p>Type de média autorisé : tout</p>
        <p>Type de document autorisé : .txt et .pdf</p>
    </div>

<?php
    }else{
        http_response_code(403);
        die('Forbidden');
    }
}else{
    http_response_code(403);
    die('Forbidden');
}
require('view_end.php');
?>