<?php
declare(strict_types=1);
require __DIR__.'/vendor/autoload.php';
use Illuminate\Support\Collection;
use Carbon\Carbon;

class sessionSix{
    public static function Main(){
        // $InvoicePath = fn()=> self::InvoicePath();
        $InvoicePath = self::InvoicePath();
        $AvailabilityPath = self::AvailabilityPath();
        $setConfigration = self::setConfiguration();
        $CostOfOrder = self::CalcAdjustedCostofOrder($setConfigration['processConfiguration'], $InvoicePath, $AvailabilityPath);
        echo $CostOfOrder($setConfigration['order']);
    }

    //Setup of the Process Configuration and Data
    public static function setConfiguration()
    {
        $processConfiguration = new ProcessConfiguration();
        $customer = new Customer();
        $order = new Order();
        $processConfiguration->invoiceChoice = InvoiceChoice::Inv3;
        $processConfiguration->shippingChoice = ShippingChoice::Sh2;
        $processConfiguration->freightChoice = FreightChoice::fr3;
        $processConfiguration->availabilityChoice = AvailabilityChoice::AV2;
        $processConfiguration->shippingDateChoice = ShippingDateChoice::SD2;
        $order->customer = $customer;
        $order->date = Carbon::now();
        $order->cost = 2000;
        return ['order'=>$order, 'processConfiguration'=>$processConfiguration];
    }
    public static function CalcAdjustedCostofOrder(ProcessConfiguration $c, $InvoicePath, $AvailabilityPath)
        {
            $InvoicePathFunc = fn($c,$fpl)=> self::InvoicePathFunc($c,$fpl);
            $AvailabilityPathFunc =fn($c,$fpl)=> self::AvailabilityPathFunc($c,$fpl);
        return fn($x) => self::AdjustCost($x, $InvoicePathFunc($c, $InvoicePath), $AvailabilityPathFunc($c, $AvailabilityPath));
        }
    //Adjusted Cost
    public static function AdjustCost(Order $r, Closure $calcFreigt, Closure $calcShippingDate)
    {
        $f = $calcFreigt($r);
        $s = $calcShippingDate($r);
        echo "Day of Shipping : ".$s->date->format("l");
        $cost = ($s->date->format("l")== "Monday") ? $f->cost + 1000 : $f->cost + 500;
        return $cost;
    }
    public static function InvoicePathFunc(ProcessConfiguration $c, $fpl)
    {
        $invoice = collect($fpl['InvoiceFunctions'])->filter(fn($x)=>$x[0] == $c->invoiceChoice)
                                    ->map((fn($x)=>$x[1]))
                                    ->first();
        $shipping = collect($fpl['ShippingFunctions'])->filter(fn($x)=>$x[0] == $c->shippingChoice)
                                            ->map((fn($x)=>$x[1]))
                                            ->first();
        $freight = collect($fpl['frieghtFunctions'])->filter(fn($x)=>$x[0] == $c->freightChoice)
                                            ->map((fn($x)=>$x[1]))
                                            ->first();
        
        $invoiceShipping= Self::Compose($invoice,$shipping);
        $f = self::Compose($invoiceShipping,$freight);
        return $f;
    }
    public static function AvailabilityPathFunc(ProcessConfiguration $c,  $fpl){

        $availabilty = collect($fpl['AvailabilityFunctions'])->filter(fn($x)=>$x[0] == $c->availabilityChoice)
                                                    ->map((fn($x)=>$x[1]))
                                                    ->first();
        $shippingDate = collect($fpl['ShippingDateFunctions'])->filter(fn($x)=>$x[0] == $c->shippingDateChoice)
                                                    ->map((fn($x)=>$x[1]))
                                                    ->first();

        $p = self::Compose($availabilty,$shippingDate);
        return $p;
    }
    public static function Compose( $i,  $s){

                return fn($x) => $s($i($x));
    }

    public static function InvoicePath(){
        $CalcInvoice1 = fn($o):Invoice=> self::calcInvoice1($o);
        $CalcInvoice2 = fn($o):Invoice=> self::calcInvoice2($o);
        $CalcInvoice3 = fn($o):Invoice=> self::calcInvoice3($o);
        $CalcInvoice4 = fn($o):Invoice=> self::calcInvoice4($o);
        $CalcInvoice5 = fn($o):Invoice=> self::calcInvoice5($o);
        $InvoiceChoiceInv1 = InvoiceChoice::Inv1;
        $InvoiceChoiceInv2 = InvoiceChoice::Inv2;
        $InvoiceChoiceInv3 = InvoiceChoice::Inv3;
        $InvoiceChoiceInv4 = InvoiceChoice::Inv4;
        $InvoiceChoiceInv5 = InvoiceChoice::Inv5;
        $InvoiceFunctions = [
            [$InvoiceChoiceInv1,$CalcInvoice1],
            [$InvoiceChoiceInv2,$CalcInvoice2],
            [$InvoiceChoiceInv3,$CalcInvoice3],
            [$InvoiceChoiceInv4,$CalcInvoice4],
            [$InvoiceChoiceInv5,$CalcInvoice5],
        ];
        $ShippingChoose1 =  ShippingChoice::Sh1;
        $ShippingChoose2 =  ShippingChoice::Sh2;
        $ShippingChoose3 =  ShippingChoice::Sh3;
        $calcShipping1 =  fn($s):Shipping=> self::calcShipping1($s);
        $calcShipping2 =  fn($s):Shipping=> self::calcShipping2($s);
        $calcShipping3 =  fn($s):Shipping=> self::calcShipping3($s);

        
        $FreightChoice1 = FreightChoice::fr1;
        $FreightChoice2 = FreightChoice::fr2;
        $FreightChoice3 = FreightChoice::fr3;
        $FreightChoice4 = FreightChoice::fr4;
        $FreightChoice5 = FreightChoice::fr5;
        $FreightChoice6 = FreightChoice::fr6;

        $calcFreightCost1 = fn($f):Freight=> self::calcFreightCost1($f);
        $calcFreightCost2 = fn($f):Freight=> self::calcFreightCost2($f);
        $calcFreightCost3 = fn($f):Freight=> self::calcFreightCost3($f);
        $calcFreightCost4 = fn($f):Freight=> self::calcFreightCost4($f);
        $calcFreightCost5 = fn($f):Freight=> self::calcFreightCost5($f);
        $calcFreightCost6 = fn($f):Freight=> self::calcFreightCost6($f);
        
        $ShippingFunctions = [
            [$ShippingChoose1,$calcShipping1],
            [$ShippingChoose2,$calcShipping2],
            [$ShippingChoose3,$calcShipping3],
        ];
        $frieghtFunctions = [
            [$FreightChoice1,$calcFreightCost1],
            [$FreightChoice2,$calcFreightCost2],
            [$FreightChoice3,$calcFreightCost3],
            [$FreightChoice4,$calcFreightCost4],
            [$FreightChoice5,$calcFreightCost5],
            [$FreightChoice6,$calcFreightCost6],
        ];
        return collect(['InvoiceFunctions'=>$InvoiceFunctions,
                        'ShippingFunctions'=>$ShippingFunctions,
                        'frieghtFunctions'=>$frieghtFunctions
                ]);

        
    }
    public static function AvailabilityPath(){
        $calcAvailability1 = fn($a):Availability=> self::calcAvailability1($a);
        $calcAvailability2 = fn($a):Availability=> self::calcAvailability2($a);
        $calcAvailability3 = fn($a):Availability=> self::calcAvailability3($a);
        $calcAvailability4 = fn($a):Availability=> self::calcAvailability4($a);
        $AvailabilityChoice1 = AvailabilityChoice::AV1;
        $AvailabilityChoice2 = AvailabilityChoice::AV2;
        $AvailabilityChoice3 = AvailabilityChoice::AV3;
        $AvailabilityChoice4 = AvailabilityChoice::AV4;
        
        $ShippingDateChoice1 = ShippingDateChoice::SD1;
        $ShippingDateChoice2 = ShippingDateChoice::SD2;
        $ShippingDateChoice3 = ShippingDateChoice::SD3;
        $ShippingDateChoice4 = ShippingDateChoice::SD4;
        $ShippingDateChoice5 = ShippingDateChoice::SD5;
        $calcShippingDate1 =fn($s):ShippingDate=> self::calcShippingDate1($s);
        $calcShippingDate2 =fn($s):ShippingDate=> self::calcShippingDate2($s);
        $calcShippingDate3 =fn($s):ShippingDate=> self::calcShippingDate3($s);
        $calcShippingDate4 =fn($s):ShippingDate=> self::calcShippingDate4($s);
        $calcShippingDate5 =fn($s):ShippingDate=> self::calcShippingDate5($s);
        $AvailabilityFunctions = [
            [$AvailabilityChoice1,$calcAvailability1],
            [$AvailabilityChoice2,$calcAvailability2],
            [$AvailabilityChoice3,$calcAvailability3],
            [$AvailabilityChoice4,$calcAvailability4],
        ];
        $ShippingDateFunctions = [
            [$ShippingDateChoice1,$calcShippingDate1],
            [$ShippingDateChoice2,$calcShippingDate2],
            [$ShippingDateChoice3,$calcShippingDate3],
            [$ShippingDateChoice4,$calcShippingDate4],
            [$ShippingDateChoice5,$calcShippingDate5],

        ];
        return collect(['AvailabilityFunctions'=>$AvailabilityFunctions,
                'ShippingDateFunctions'=>$ShippingDateFunctions
            ]);

    }

    public static function calcInvoice1(Order $o):Invoice
    {
        echo "Invoice 1".PHP_EOL;
        $invoice = new Invoice();
        $invoice->cost = $o->cost * 1.1;
        return $invoice;
    }
    public static function calcInvoice2(Order $o):Invoice
    {
        echo "Invoice 2".PHP_EOL;
        $invoice = new Invoice();
        $invoice->cost = $o->cost * 1.2;
        return $invoice;
    }
    public static function calcInvoice3(Order $o):Invoice
    {
        echo "Invoice 1".PHP_EOL;
        $invoice = new Invoice();
        $invoice->cost = $o->cost * 1.3;
        return $invoice;
    }
    public static function calcInvoice4(Order $o):Invoice
    {
        echo "Invoice 1".PHP_EOL;
        $invoice = new Invoice();
        $invoice->cost = $o->cost * 1.4;
        return $invoice;
    }
    public static function calcInvoice5(Order $o):Invoice
    {
        echo "Invoice 1".PHP_EOL;
        $invoice = new Invoice();
        $invoice->cost = $o->cost * 1.5;
        return $invoice;
    }

    public static function calcShipping1(Invoice $o):Shipping
    {
        echo "Shipping 1".PHP_EOL;
        $s = new Shipping();
        $s->ShipperID = ($o->cost > 1000) ? 1 : 2;
        $s->cost = $o->cost;

        return $s;
    }
    public static function calcShipping2(Invoice $o):Shipping
    {
        echo "Shipping 2".PHP_EOL;
        $s = new Shipping();
        $s->ShipperID = ($o->cost > 1100) ? 1 : 2;
        $s->cost = $o->cost;
        return $s;
    }
    public static function calcShipping3(Invoice $o):Shipping
    {
        echo "Shipping 3".PHP_EOL;
        $s = new Shipping();
        $s->ShipperID = ($o->cost > 1200) ? 1 : 2;
        $s->cost = $o->cost;
        return $s;
    }

    public static function calcFreightCost1(Shipping $s):Freight
    {
        echo "Freight 1".PHP_EOL;
        $f = new Freight();
        $f->cost = ($s->ShipperID == 1) ? $s->cost * 0.25 : $s->cost * 0.5;
        return $f;
    }
    public static function calcFreightCost2(Shipping $s):Freight
    {
        echo "Freight 2".PHP_EOL;
        $f = new Freight();
        $f->cost = ($s->ShipperID == 1) ? $s->cost * 0.28 : $s->cost * 0.52;
        return $f;
    }
    public static function calcFreightCost3(Shipping $s):Freight
    {
        echo "Freight 3".PHP_EOL;
        $f = new Freight();
        $f->cost = ($s->ShipperID == 1) ? $s->cost * 0.3 : $s->cost * 0.6;
        return $f;
    }
    public static function calcFreightCost4(Shipping $s):Freight
    {
        echo "Freight 4".PHP_EOL;
        $f = new Freight();
        $f->cost = ($s->ShipperID == 1) ? $s->cost * 0.35 : $s->cost * 0.65;
        return $f;
    }
    public static function calcFreightCost5(Shipping $s):Freight
    {
        echo "Freight 5".PHP_EOL;
        $f = new Freight();
        $f->cost = ($s->ShipperID == 1) ? $s->cost * 0.15 : $s->cost * 0.2;
        return $f;
    }
    public static function calcFreightCost6(Shipping $s):Freight
    {
        echo "Freight 6".PHP_EOL;
        $f = new Freight();
        $f->cost = ($s->ShipperID == 1) ? $s->cost * 0.1 : $s->cost * 0.15;
        return $f;
    }

    public static function calcAvailability1(Order $o):Availability
    {
        echo "Availability 1".PHP_EOL;
        $a = new Availability();
        $a->date = $o->date->addDay(3);
        return $a;
    }
    public static function calcAvailability2(Order $o):Availability
    {
        echo "Availability 2".PHP_EOL;
        $a = new Availability();
        $a->date = $o->date->addDay(2);
        return $a;
    }
    public static function calcAvailability3(Order $o):Availability
    {
        echo "Availability 3".PHP_EOL;
        $a = new Availability();
        $a->date = $o->date->addDay(1);
        return $a;
    }
    public static function calcAvailability4(Order $o):Availability
    {
        echo "Availability 4".PHP_EOL;
        $a = new Availability();
        $a->date = $o->date->addDay(4);
        return $a;
    }


    public static function calcShippingDate1(Availability $o):ShippingDate
    {
        echo "ShippingDate 1".PHP_EOL;
        $a = new ShippingDate();
        $a->date = $o->date->addDay(1);
        return $a;
    }
    public static function calcShippingDate2(Availability $o):ShippingDate
    {
        echo "ShippingDate 2".PHP_EOL;
        $a = new ShippingDate();
        $a->date = $o->date->addDay(1);
        return $a;
    }
    public static function calcShippingDate3(Availability $o):ShippingDate
    {
        echo "ShippingDate 3".PHP_EOL;
        $a = new ShippingDate();
        $a->date = $o->date->AddHours(15);
        return $a;
    }
    public static function calcShippingDate4(Availability $o):ShippingDate
    {
        echo "ShippingDate 4".PHP_EOL;
        $a = new ShippingDate();
        $a->date = $o->date->AddHours(20);
        return $a;
    }


    public static function calcShippingDate5(Availability $o):ShippingDate
    {
        echo "ShippingDate 3".PHP_EOL;
        $a = new ShippingDate();
        $a->date = $o->date->AddHours(10);
        return $a;
    }
}
#endregion
class ProcessConfiguration{
    public  $invoiceChoice ;
    public  $shippingChoice ;
    public  $freightChoice ;
    public  $availabilityChoice ;
    public  $shippingDateChoice ; 
    }
    
class Customer{
        
}
class Order{
        public Customer  $Customer ;
        public   $date ;
        public float $cost ;
        
}
class Invoice{
    public $cost;
    public function __construct()
    {
        $this->cost =0;   
    }
}
class Shipping{
    public $cost;
    public $shipperId;

    public function __construct()
    {
        $this->cost =0;   
    }
}
class Freight{
    public $cost;
    public function __construct()
    {
        $this->cost =0;   
    }
}
class Availability
    {
        public  $date;
    }
    class ShippingDate
    {
        public  $date;
    }
enum InvoiceChoice{
        const Inv1 = 0;
        const Inv2 = 1;
        const Inv3 = 2;
        const Inv4 = 3;
        const Inv5 = 4;
}
enum ShippingChoice{
        case Sh1;
        case Sh2;
        case Sh3;
}
enum FreightChoice{
        case fr1;
        case fr2;
        case fr3;
        case fr4;
        case fr5;
        case fr6;
}
enum AvailabilityChoice{
        case AV1;
        case AV2;
        case AV3;
        case AV4;
}
enum ShippingDateChoice{
        case SD1;
        case SD2;
        case SD3;
        case SD4;
        case SD5;
}

$sessionOne = sessionSix::main();

