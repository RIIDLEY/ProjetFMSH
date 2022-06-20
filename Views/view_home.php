<?php
require('view_begin.php');
?>
    <h1>Coucou</h1>
    <form action = "?controller=home&action=upload" method="post" enctype="multipart/form-data">
        <input type="file" name="fichier">
        <input type="submit" value="Envoyer"/>
    </form>

<?php
require('view_end.php');
?>