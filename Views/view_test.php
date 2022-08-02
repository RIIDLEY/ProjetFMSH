<?php
require('view_begin.php');

print_r($liste);

if (isset($liste)){
    foreach($liste as $data){
?>
        <div class="videoSimi">
            <p> Titre : <?=$data["Name"]?></p>
            <p> Description : <?=$data["Description"]?></p>
        </div>

        <?php
    }
}


require('view_end.php');
?>
