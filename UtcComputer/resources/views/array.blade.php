<?php
function soptchiahetcho3($arr) {
    $c=0;
    for ($i = 0; $i < count($arr); $i++) {
        if($arr[$i]%3==0){
            $c++;
        }
    }
    return "số phần tử chia hết cho 3 là :{$c}";
}
echo soptchiahetcho3(array(1,3,6,9,10))
?>