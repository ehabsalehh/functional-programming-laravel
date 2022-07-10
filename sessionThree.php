<?php
declare(strict_types=1);

class sessionThree
{
    public static function main(){
            $delagateOne = fn($number):float=> $this->testOne($number);
            $delagateTwo = fn($number):float=> $this->testTwo($number);
            $delagatehree = fn($numberone,$numberTwo):float=> $this->testThree($numberone,$numberTwo);
            $list = [$delagateOne,$delagateTwo];
            echo $delagateOne(5).PHP_EOL;
            echo $delagateTwo(5).PHP_EOL;
            echo $list[0](5).PHP_EOL;
            echo $list[1](5).PHP_EOL;
            echo $delagatehree($delagateOne,5).PHP_EOL;
    }
        
    // session Two
    public function testOne(float $number) :float
    {
        return $number/2;
    }
    public function testTwo(float $number):float 
    {
        return $number/4 + 1;
    }
    public function testThree(Closure $fun,float $number):float{
        return $fun($number) + $number;
    }
}    
sessionThree::main();