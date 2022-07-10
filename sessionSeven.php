<?php
declare(strict_types=1);
require __DIR__.'/vendor/autoload.php';
use Illuminate\Support\Collection;
class sessionSeven{
    public static function main(){
        $segementsSalary = collect([
            ['segment'=>'a','BasicSalary' =>1000],
            ['segment'=>'b','BasicSalary' =>2000],
            ['segment'=>'c','BasicSalary' =>3000],            
        ]);
         $grossSalaryCalculators = $segementsSalary->map(fn($a):array=>
                                    [
                                        $a['segment'],
                                        'MyGrossSalaryCalculator'=>self::GrossSalaryCalculator($a['BasicSalary'])
                                    ]);
        echo $grossSalaryCalculators[0]['MyGrossSalaryCalculator'](80).PHP_EOL;
        echo $grossSalaryCalculators[1]['MyGrossSalaryCalculator'](80).PHP_EOL;
        echo $grossSalaryCalculators[2]['MyGrossSalaryCalculator'](80).PHP_EOL;
    }
    public  static function GrossSalaryCalculator(float $basicSalary):Closure{
        $tax = 0.2 * $basicSalary;
        return fn(float $bonus)=> $basicSalary + $tax + $bonus;
    }
}
echo sessionSeven::main();
