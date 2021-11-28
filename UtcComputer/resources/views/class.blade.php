<?php
class SoPhuc{
    private $a;
    private $b;
    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->a = $b;
    }
    public function Tong($a1, $b1){
        return new SoPhuc($this->a+$a1,$this->b+$b1);
    }
    public function Moduls(){
        return sqrt ( $this->a*$this->a+$this->b*$this->b );
    }
}

class ListSoPhuc{
    private $listSP;
    
    public function Show(){
        $str="";
        foreach ($this->listSP as &$value) {
            $str+="{$value['a']} + {$value['b']}i; "
        }
    }
    public function Moduls(){
        return sqrt ( $this->a*$this->a+$this->b*$this->b );
    }
}
?>