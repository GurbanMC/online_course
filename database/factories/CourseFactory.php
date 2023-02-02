<?php

namespace Database\Factories;

use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Course $course) {
            //
        })->afterCreating(function (Course $course) {
            //
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $category = Category::doesntHave('child')->inRandomOrder()->first();

        $level = fake()->boolean(90)
            ? AttributeValue::where('attribute_id', 1)->inRandomOrder()->first() : null;

        $nameTm = fake()->streetSuffix();
        $nameEn = null;

        $fullNameTm = $nameTm . ' ';
        $fullNameEn = ($nameEn ?: $nameTm) . ' ';

        $hasDiscount = fake()->boolean(20);

        return [
            'category_id' => $category->id,
            'level_id' => isset($level) ? $level->id : null,
            'code' => 'c' . $category->id
                . (isset($level) ? '-g' . $level->id : ''),
            'name_tm' => $nameTm,
            'name_en' => $nameEn,
            'full_name_tm' => $fullNameTm,
            'full_name_en' => $fullNameEn,
            'slug' => str()->slug($fullNameTm) . '-' . str()->random(10),
            'price' => fake()->randomFloat($nbMaxDecimals = 1, $min = 10, $max = 100),
            'discount_percent' => $hasDiscount
                ? rand(10, 20) : 0,
            'discount_start' => $hasDiscount
                ? Carbon::today()
                    ->subDays(rand(1, 7))
                    ->subHours(rand(1, 24))
                    ->subMinutes(rand(1, 60))
                    ->toDateTimeString()
                : Carbon::today()
                    ->startOfMonth()
                    ->toDateTimeString(),
            'discount_end' => $hasDiscount
                ? Carbon::today()
                    ->addDays(rand(1, 7))
                    ->addHours(rand(1, 24))
                    ->addMinutes(rand(1, 60))
                    ->toDateTimeString()
                : Carbon::today()
                    ->startOfMonth()
                    ->toDateTimeString(),
            'description' => fake()->text(rand(300, 500)),
            'favorites' => rand(0, 30),
            'viewed' => rand(20, 100),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
        ];
    }
}
