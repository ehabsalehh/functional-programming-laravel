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
       return $this->numbers->map([$this,'addOne'])
                            ->map([$this,'square'])
                            ->map([$this,'subtractTen'])
                            ->filter(fn($number)=>$number<20)
                            ->sort()
                            ->take(2);
    }
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
