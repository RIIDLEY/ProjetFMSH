<?php



function stripAccents($str) {
    return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}


function getSizeTags($minOccu,$maxOccu,$OccuCourant){
    $rectif = 1;
    if ($maxOccu-$minOccu<=0){
        $a = ((50-15)/1);
    }else{
        $a = ((50-15)/($maxOccu-$minOccu));
    }
    $b = 15-$a;
    if ($minOccu>=4){
        $rectif = 2;
    }
    return ($a*$OccuCourant+$b)/$rectif;
}

function remove_emoji($string) {
    $symbols = "\x{1F100}-\x{1F1FF}" // Enclosed Alphanumeric Supplement
        ."\x{1F300}-\x{1F5FF}" // Miscellaneous Symbols and Pictographs
        ."\x{1F600}-\x{1F64F}" //Emoticons
        ."\x{1F680}-\x{1F6FF}" // Transport And Map Symbols
        ."\x{1F900}-\x{1F9FF}" // Supplemental Symbols and Pictographs
        ."\x{2600}-\x{26FF}" // Miscellaneous Symbols
        ."\x{2700}-\x{27BF}"; // Dingbats

    return preg_replace('/['. $symbols . ']+/u', '', $string);
}
