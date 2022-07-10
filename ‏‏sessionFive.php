<?php
declare(strict_types=1);
require __DIR__.'/vendor/autoload.php';
use Illuminate\Support\Collection;
class sessionOne{
    private $numbers;
    public function __construct()
    {
        $this->numbers = collect([7, 4, 5, 6, 3, 8, 10]);
    }
    public function main(){
        $addOne = fn($number):float=> $this->addOne($number);
        $square = fn($number):float=> $this->square($number);
        $subtractTen = fn($number):float=> $this->subtractTen($number);
        $mycomposedFunction = fn($numberone,$numberTwo,$numberThree)=>
                                 $this->composedFunction($numberone,$numberTwo,$numberThree);
    
        return $this->numbers->map($mycomposedFunction($addOne,$square,$subtractTen))
                                ->filter(fn($number)=>$number<20)
                                ->sort()
                                ->take(2);
    }
    public function composedFunction($addOne,$square,$subtractTen){
        return fn($x)=> $subtractTen($square($addOne($x)));
    }
    // session one
    public function addOne(int $number):int{
        return $number+1;
    }
    public function square(int $number):int
    {
        return $number * $number;
    }
    public function subtractTen($number):int
    {
        return $number - 10;
    }
}
$sessionOne = new sessionOne;
print_r($sessionOne->main()->toArray());

