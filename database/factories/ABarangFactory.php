<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ABarang;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ABarang>
 */
class ABarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $randInit =  $this->faker->numberBetween(1, 6);

        if ($randInit === 1) {
            $randName = Arr::random(['ProArt', 'Zenbook', 'ROG', 'TUF', 'ZEPHYRUS']);
            return [
                'Product_Name' => $randName,
                'Brand' => "Asus",
                'Price' => $this->faker->numberBetween(5000000, 25000000),
                'Model_No' => $this->faker->unique()->numerify('ASS-####'),
            ];
        }
        elseif ($randInit === 2){
            $randName = Arr::random(['Elitebook', 'Envy', 'Omen', 'Pavilion']);
            return [
                'Product_Name' => $randName,
                'Brand' => "HP",
                'Price' => $this->faker->numberBetween(5000000, 15000000),
                'Model_No' => $this->faker->unique()->numerify('HPE-####'),
            ];
        }
        elseif ($randInit === 3){
            $randName = Arr::random(['IdeaPad', 'Legion', 'ThinkPad', 'ThinkBook', 'Yoga']);
            return [
                'Product_Name' => $randName,
                'Brand' => "Lenovo",
                'Price' => $this->faker->numberBetween(5000000, 15000000),
                'Model_No' => $this->faker->unique()->numerify('LNV-####'),
            ];
        }
        elseif ($randInit === 4){
            $randName = Arr::random(['Alienware', 'G Series', 'Inspiron', 'Latitude', 'Precision', 'Vostro', 'XPS']);
            return [
                'Product_Name' => $randName,
                'Brand' => "Dell",
                'Price' => $this->faker->numberBetween(5000000, 15000000),
                'Model_No' => $this->faker->unique()->numerify('DLL-####'),
            ];
        }
        elseif ($randInit === 5){
            $randName = Arr::random(['Aspire', 'Enduro', 'Nitro', 'Predator', 'Swift', 'TravelMate']);
            return [
                'Product_Name' => $randName,
                'Brand' => "Acer",
                'Price' => $this->faker->numberBetween(5000000, 15000000),
                'Model_No' => $this->faker->unique()->numerify('ACR-####'),
            ];
        }
        elseif ($randInit === 6){
            $randName = Arr::random(['MacBook', 'MacBook Air', 'MacBook Pro']);
            return [
                'Product_Name' => $randName,
                'Brand' => "Apple",
                'Price' => $this->faker->numberBetween(5000000, 15000000),
                'Model_No' => $this->faker->unique()->numerify('APL-####'),
            ];
        }

        // Fallback if somehow rand number exceed max limit
        else{
            return [
                'Product_Name' => "Error",
                'Brand' => "Error",
                'Price' => 0,
                'Model_No' => $this->faker->numerify('ZZZ-####'),
            ];
        }

    }
}
