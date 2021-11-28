<?php
function fibonacci($n) {
    $f0 = 0;
    $f1 = 1;
    $fn = 1;
 
    if ($n == 0 || $n == 1) {
        return $n;
    } else {
        for($i = 2; $i < $n; $i ++) {
            $f0 = $f1;
            $f1 = $fn;
            $fn = $f0 + $f1;
            //if($fn>=$n && $n>=$f1){
            //    return ($fn-$n<$n-$f1)?$fn:$f1;
            //}
        }
    }
    return $fn;
}
echo fibonacci(6)
?>