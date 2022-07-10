<?php
declare(strict_types=1);

class sessionThreePartTwo
{
    private $food;
    private $beverage;
    private $rawMeterial;
    private $order;
    private $product;
    public function __construct()
    {
        $this->food = fn($number):array=> self::ProductParamtersFood($number);
        $this->beverage = fn($number):array=> self::ProductParamtersBeverage($number);
        $this->rawMeterial = fn($number):array=> self::ProductParamtersRawMetarial($number);
        $this->order = ['id'=>10,'index'=>100,'quantity'=>20,'price'=>200];
    }
    public function main(){
        $this->product = 'food';
        $parameters = ($this->product == 'food')?$this->food:(($this->product == 'beverage')?$this->beverage:$this->rawMeterial);
        return $this->calaulateDiscount($parameters,$this->order);
    }
    public function calaulateDiscount(Closure $func, $order){
        $params = $func($order['index']);
        return $params['x1']* $order['quantity']+$params['x2']* $order['price'];
    }
    
    public static function ProductParamtersFood(int $productIndex){
        $x1 = $productIndex/($productIndex+100);
        $x2 = $productIndex/($productIndex+300);
        return array('x1'=>$x1,'x2'=>$x2);
    }
    public static function ProductParamtersBeverage(int $productIndex){
        $x1 = $productIndex/($productIndex+300);
        $x2 = $productIndex/($productIndex+400);
        return array('x1'=>$x1,'x2'=>$x2);
    }
    public static function ProductParamtersRawMetarial(int $productIndex){
        $x1 = $productIndex/($productIndex+400);
        $x2 = $productIndex/($productIndex+700);
        return array('x1'=>$x1,'x2'=>$x2);
    }
 
    
}    
$sessionThreePartTwo = new sessionThreePartTwo;
print_r($sessionThreePartTwo->main()->toArray());
