<?php
require('view_begin.php');
?>

    <h1>Coucou</h1>
    <form action = "?controller=home&action=upload" method="post" enctype="multipart/form-data">
        <input type="text" name="Name" placeholder="Nom du document" required>
        <input type="text" name="Description" placeholder="Description" required>
        <input type="text" name="tags" placeholder="Mots-clés" >
        <input type="file" name="fichier">
        <label>Type : </label>
        <select name="type">
            <option>-</option>
            <option>Média</option>
            <option>Document</option>
        </select>
        <input type="submit" value="Envoyer"/>
    </form>

    </script>
<?php
require('view_end.php');
?>