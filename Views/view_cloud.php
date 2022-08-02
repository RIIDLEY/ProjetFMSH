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
                        <a href=<?=$TranscriptFile?> class="btn btn-primary" download="<?=explode("/", $TranscriptFile)[2]?>">Transcription</a>
                        <a href=<?=$PathFile?> class="btn btn-primary" download="<?=explode("/", $PathFile)[2]?>">Télécharger la vidéo</a>
                    </center>
                <?php
            }elseif (isset($TranscriptFile) and $TranscriptFile === "None" and isset($PathFile) and isset($Extension)){
                if ($Extension==="txt"){?>
                        <center>
                            <object data="<?=$PathFile?>" width="80%" height=auto style="padding: 1em">
                                Not supported
                            </object>
                            <a href=<?=$PathFile?> class="btn btn-primary" download="<?=explode("/", $PathFile)[2]?>">Télécharger le document</a>
                        </center>
                        <?php
                }else{?>
                    <center>
                        <iframe src="<?=$PathFile?>#toolbar=0"  width="100%" height="500px" style="padding: 1em"> </iframe>
                        <a href=<?=$PathFile?> class="btn btn-primary" download="<?=explode("/", $PathFile)[2]?>">Télécharger le document</a>
                    </center>
                    <?php

                }
            }
                if (isset($Name) and isset($Tags) and isset($Description) and isset($TranscriptFile)){
                ?>
                <p><u><strong>Nom :</strong></u> <?=$Name?></p>
                <p><u><strong>Tags :</strong></u> <?= str_replace(","," | ",$Tags)?></p>
                <p><u><strong>Description :</strong></u> <?=$Description?></p>
                <?php
                }
             ?>
            </div>
            <div class="col" style="display: flex; align-items: center; justify-content: center;" >
                <div style="padding: 70px 0; text-align: center; margin-bottom: 6%; margin-top: 6%">
                <u><h1>Nuage de mots :</h1></u>
                <?php
                if (isset($tabWord)){
                    shuffle($tabWord);
                    $MaxOccu = max(array_column($tabWord, 'Occurence'));
                    $MinOccu = min(array_column($tabWord, 'Occurence'));
                    $i=0;
                    foreach ($tabWord as $valueArray) {
                        echo '<span style="font-size:'.getSizeTags($MinOccu,$MaxOccu,$valueArray["Occurence"]).'px; display:inline">'.$valueArray["Word"].'&nbsp;</span>';
                        $i++;
                        if($i%6==0){
                            echo '<br>';
                        }
                    }
                }
                ?>
                </div>
            </div>
        </div>
    <hr>
    <div class="row DivVideoSimi">
        <div class="col">
            <div style="padding: 70px 0; text-align: center;">
                <u><h2>Documents similaires :</h2></u>
            <?php

            if (isset($ListeDocuSim)){

                if (!empty($ListeDocuSim)){
                $i=0;
                foreach($ListeDocuSim as $data){
                    ?>
                    <a href="<?=$data["FileID"]?>" style="text-decoration: none;">
                    <div class="videoSimi">
                        <p> Titre : <?=$data["Name"]?></p>
                        <p> Description : <?=$data["Description"]?></p>
                    </div>
                    </a>
                    <?php
                    $i++;
                    if($i%3==0){
                        echo '<div style="clear:both"></div>';
                    }
                }
                }else{?>
                    <h5>Aucun document similaire trouvé.</h5>
                <?php }
            }

            ?>
            </div>
        </div>
    </div>

    </div>




</div>



<?php
require('view_end.php');
?>
