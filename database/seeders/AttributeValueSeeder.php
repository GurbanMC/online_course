<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $objs = [
            ['name_tm' => 'Dereje', 'name_en' => 'Level', 'course_name' => true, 'values' => [
                ['name_tm' => 'Başlangyç', 'name_en' => 'Elementary'],
                ['name_tm' => 'Orta', 'name_en' => 'Middle'],
                ['name_tm' => 'Hünärmen', 'name_en' => 'Professional'],
            ]],
        ];

        for ($i = 0; $i < count($objs); $i++) {
            $attribute = Attribute::create([
                'name_tm' => $objs[$i]['name_tm'],
                'name_en' => $objs[$i]['name_en'],
                'course_name' => $objs[$i]['course_name'],
                'sort_order' => $i + 1,
            ]);

            for ($j = 0; $j < count($objs[$i]['values']); $j++) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'name_tm' => $objs[$i]['values'][$j]['name_tm'],
                    'name_en' => $objs[$i]['values'][$j]['name_en'],
                    'sort_order' => $j + 1,
                ]);
            }
        }
    }
}
