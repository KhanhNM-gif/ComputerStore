<?php
function xulystring($str) {
    return strrev($str.stripslashes('a'));
}
function xulystring2($str) {
    $c=0;
    $arr=explode(' ', $str);
    for ($i = 0; $i < count($arr); $i++) {
        if(!is_numeric($arr[$i]))return "false";
        $c++;
    }
    return "true {$c}";
}
//echo xulystring('Lap trinh PHP khong don gian')

echo xulystring2("2 3 -4 5.5")
?>