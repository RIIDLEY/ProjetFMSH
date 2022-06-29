<?php


function stripAccents($str) {
    return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}


function getSizeTags($minOccu,$maxOccu,$OccuCourant){
    $a = ((50-10)/($maxOccu-$minOccu));
    $b = 10-$a;
    return $a*$OccuCourant+$b;
}