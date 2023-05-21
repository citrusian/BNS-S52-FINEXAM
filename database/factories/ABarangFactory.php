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
        $randInit = $this->faker->numberBetween(1, 6);

        $brands = [
            1 => [
                'brand' => 'Asus',
                'names' => ['ProArt', 'Zenbook', 'ROG', 'TUF', 'ZEPHYRUS'],
                'modelPrefix' => 'ASS',
                'priceRange' => [5000000, 25000000],
            ],
            2 => [
                'brand' => 'HP',
                'names' => ['Elitebook', 'Envy', 'Omen', 'Pavilion'],
                'modelPrefix' => 'HPE',
                'priceRange' => [5000000, 15000000],
            ],
            3 => [
                'brand' => 'Lenovo',
                'names' => ['IdeaPad', 'Legion', 'ThinkPad', 'ThinkBook', 'Yoga'],
                'modelPrefix' => 'LNV',
                'priceRange' => [5000000, 15000000],
            ],
            4 => [
                'brand' => 'Dell',
                'names' => ['Alienware', 'G Series', 'Inspiron', 'Latitude', 'Precision', 'Vostro', 'XPS'],
                'modelPrefix' => 'DLL',
                'priceRange' => [5000000, 15000000],
            ],
            5 => [
                'brand' => 'Acer',
                'names' => ['Aspire', 'Enduro', 'Nitro', 'Predator', 'Swift', 'TravelMate'],
                'modelPrefix' => 'ACR',
                'priceRange' => [5000000, 15000000],
            ],
            6 => [
                'brand' => 'Apple',
                'names' => ['MacBook', 'MacBook Air', 'MacBook Pro'],
                'modelPrefix' => 'APL',
                'priceRange' => [5000000, 15000000],
            ],
        ];

        if (isset($brands[$randInit])) {
            $brandData = $brands[$randInit];

            $randName = Arr::random($brandData['names']);

            return [
                'Product_Name' => $randName,
                'Brand' => $brandData['brand'],
                'Price' => $this->faker->numberBetween($brandData['priceRange'][0], $brandData['priceRange'][1]),
                'Model_No' => $this->faker->unique()->numerify($brandData['modelPrefix'].'-####'),
            ];
        }

        // Fallback if somehow rand number exceed max limit
        return [
            'Product_Name' => 'Error',
            'Brand' => 'Error',
            'Price' => 0,
            'Model_No' => $this->faker->numerify('ZZZ-####'),
        ];

    }
}
