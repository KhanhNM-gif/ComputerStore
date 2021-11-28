<?php
function giaiPTB2($a, $b, $c) {
    if ($a == "")
        $a = 0;
    if ($b == "")
        $b = 0;
    if ($c == "")
        $c = 0;
    echo "Phương trình: " . $a . "x2 + " . $b . "x + " . $c . " = 0<br>";
    // kiểm tra các hệ số
    if ($a == 0) {
        if ($b == 0) {
            return ("Phương trình vô nghiệm!");
        } else {
            return ("Phương trình có một nghiệm: " . "x = " . (- $c / $b));
        }
        return;
    }
    // tính delta
    $delta = $b * $b - 4 * $a * $c;
    $x1 = "";
    $x2 = "";
    // tính nghiệm
    if ($delta > 0) {
        $x1 = (- $b + sqrt ( $delta )) / (2 * $a);
        $x2 = (- $b - sqrt ( $delta )) / (2 * $a);
        return ("Phương trình có 2 nghiệm là: " . "x1 = " . $x1 . " và x2 = " . $x2);
    } else if ($delta == 0) {
        $x1 = (- $b / (2 * $a));
        return ("Phương trình có nghiệm kép: x1 = x2 = " . $x1);
    } else {
        return ("Phương trình vô nghiệm!");
    }
}
echo giaiPTB2(1,2,1)
?>