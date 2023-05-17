<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ANomorSeri>
 */
class ANomorSeriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // get max id
        $maxproduct = DB::table('a_barangs')->count();
        // get rand number between 1 - $maxproduct
        $rand_id = $this->faker->unique()->numberBetween(1, $maxproduct);
        // get Model_No to be used in Product_Id
        $Product_id = DB::table('a_barangs')->where('id', $rand_id)->value('Model_No');
        // get price using Model_No FK
        $price = DB::table('a_barangs')->where('Model_No', $Product_id)->value('Price');
        // rand production date between last year and last 6 month
        $Prod_date = $this->faker->dateTimeInInterval('-1 years', '+6 months');

        // rand used status
        $used = $this->faker->numberBetween(0,1);

        if ($used === 1){
            $Warranty_Start = $this->faker->dateTimeThisYear();
            $date = Carbon::parse($Warranty_Start);
            $Warranty_Duration = $date->addYears(2);
        }
        else{
            $Warranty_Start = NULL;
            $Warranty_Duration =  NULL;
        }

        return [
            'Product_id' => $Product_id,
            'Serial_no' => $this->faker->ean13(),
            'Price' => $price,
            'Prod_date' => $Prod_date,
            'Warranty_Start' => $Warranty_Start,
            'Warranty_Duration' => $Warranty_Duration,
            'Used' => $used,
        ];
    }
}
