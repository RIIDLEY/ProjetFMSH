<?php


function stripAccents($str) {
    return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}


function getSizeTags($minOccu,$maxOccu,$OccuCourant){
    if ($maxOccu-$minOccu<=0){
        $a = ((50-15)/1);
    }else{
        $a = ((50-15)/($maxOccu-$minOccu));
    }
    $b = 15-$a;
    return $a*$OccuCourant+$b;
}