<?php
require('view_begin.php');
?>



<div class="grandeDivData">
    <u><h1>Nuage de mots :</h1></u>
    <?php
    if (isset($tab)){
        $MaxOccu = max(array_column($tab, 'Occurence'));
        $MinOccu = min(array_column($tab, 'Occurence'));
        $i=0;
        foreach ($tab as $valueArray) {
            echo '<span style="font-size:'.getSizeTags($MinOccu,$MaxOccu,$valueArray["Occurence"]).'px; padding: 1em;">'.$valueArray["Word"].'</span>';
            $i++;
            if($i%6==0){
                echo "<br>";
            }
        }

    }
    ?>

</div>

<?php
require('view_end.php');
?>
