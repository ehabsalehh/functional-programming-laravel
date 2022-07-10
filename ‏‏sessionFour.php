<?php
declare(strict_types=1);
use Illuminate\Support\Collection;

class sessionFour
{
    public  function main(){
        $OrdersList = collect([
            new Order(['order_number'=>'ORD-UM']),
            new Order(['order_number'=>'ORD-UMMSE']),
        ]);
        return  $OrdersList->map( function($order){
            return $this->getOrderWithDiscount($order,$this->GetDiscountRules());
        })->toArray();            
    }
    public function getOrderWithDiscount(order $order,Collection $Rules){
        $discount = $Rules->filter(fn($a)=> $a['QualifyingCondition']($order))
                            ->map(fn($b)=>$b['GetDiscount']($order))
                            ->sort()
                            ->take(3)
                            ->avg();
        $newOrder = new order($order->data);
        $newOrder->discount =$discount;
        return $newOrder;

    }

    public function GetDiscountRules():Collection{
        return collect([
            [
                'QualifyingCondition' =>[$this,'IsAQualified'],
                'GetDiscount' =>[$this,'A']
            ],
            [
                'QualifyingCondition' =>[$this,'IsBQualified'],
                'GetDiscount' =>[$this,'B']
            ],
            [
                'QualifyingCondition' =>[$this,'IsCQualified'],
                'GetDiscount' =>[$this,'C']
            ]
        ]);
    }
    public function IsAQualified(Order $order):bool{
        return true;
    }
    public function A(Order $order):float{
        return 1.0;
    }
    public function IsBQualified(Order $order):bool{
        return true;
    }
    public function B(Order $order):float{
        return 1.0;
    }
    public function IsCQualified(Order $order):bool{
        return true;
    }
    public function C(Order $order):float{
        return 1.0;
    }
        
   
}    
class Order
{
    public float $discount;

    public array $data;

    public function __construct($data)
    {

        $this->data = $data;
    }
}
