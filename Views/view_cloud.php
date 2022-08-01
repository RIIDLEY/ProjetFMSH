<?php
require('view_begin.php');
?>


<div class="container">
    <div class="row DivData">
        <div class="col InfoFile">
            <?php
            if (isset($TranscriptFile) and $TranscriptFile != "None" and isset($PathFile) and isset($Extension)){
                ?>
                    <center>
                <video width="80%" controls style="padding: 1em">
                    <source src="<?=$PathFile?>" type="video/<?=$Extension?>">
                </video>
                    </center>
                <?php
            }
                if (isset($Name) and isset($Tags) and isset($Description) and isset($TranscriptFile)){
                ?>
                <p><u><strong>Nom :</strong></u> <?=$Name?></p>
                <p><u><strong>Tags :</strong></u> <?= str_replace(","," | ",$Tags)?></p>
                <p><u><strong>Description :</strong></u> <?=$Description?></p>
                <a href=<?=$TranscriptFile?> class="btn btn-primary" download="<?=explode("/", $TranscriptFile)[2]?>">Transcription</a>
                <?php
                }
             ?>
            </div>
            <div class="col" style="text-align: center; margin-bottom: 6%; margin-top: 6%">
                <u><h1>Nuage de mots :</h1></u>
                <?php
                if (isset($tabWord)){
                    shuffle($tabWord);
                    $MaxOccu = max(array_column($tabWord, 'Occurence'));
                    $MinOccu = min(array_column($tabWord, 'Occurence'));
                    $i=0;
                    foreach ($tabWord as $valueArray) {
                        echo '<span style="font-size:'.getSizeTags($MinOccu,$MaxOccu,$valueArray["Occurence"]).'px;">'.$valueArray["Word"].'&nbsp;</span>';
                        $i++;
                        if($i%6==0){
                            echo '<div style="clear:both"></div>';
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>



<?php
require('view_end.php');
?>
