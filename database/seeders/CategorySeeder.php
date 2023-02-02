<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        {
            $objs = [
                ['Biznes we söwda', null, [
                    ['Biznes ýöretmek', null, 'Sport Aýakgap', null],
                    ['Buhgalteriýa', null, 'Ädik', null],
                    ['Marketing', null, 'Şypbyk', null],
                    ['Önümçilik', null, 'Sport Aýakgap', null],
                    ['Söwda we satuw', null, 'Ädik', null],
                    ['Söwda we syýahatçylyk', null, 'Şypbyk', null],
                ]],
                ['Dizaýn', null, [
                    ['3D we animasiýa', null, 'Sagat', null],
                    ['Adobe programmalry', null, 'Äýnek', null],
                    ['Arhitektura we inženerçilik', null, 'Kemer', null],
                    ['Web dizaýn', null, 'Gapjyk', null],
                ]],
                ['Informatika we internet', null, [
                    ['Informatika', null, 'Sagat', null],
                    ['Internet we tor howpsuzlygy', null, 'Äýnek', null],
                    ['Kompýuter programmalary', null, 'Kemer', null],
                    ['Täze tehnologiýalar', null, 'Gapjyk', null],
                ]],
                ['Programmirleme', null, [
                    ['Mobil priloženiýalary düzmek', null, 'Sagat', null],
                    ['Oýunlary düzmek', null, 'Äýnek', null],
                    ['Programmirleme dilleri', null, 'Kemer', null],
                    ['Robotlar tehnologiýasy', null, 'Gapjyk', null],
                    ['Web programmirleme', null, 'Gapjyk', null],
                ]],
            ];

            for ($i = 0; $i < count($objs); $i++) {
                $category = Category::create([
                    'name_tm' => $objs[$i][0],
                    'name_en' => $objs[$i][1],
                    'sort_order' => $i + 1,
                ]);

                for ($j = 0; $j < count($objs[$i][2]); $j++) {
                    Category::create([
                        'parent_id' => $category->id,
                        'name_tm' => $objs[$i][2][$j][0],
                        'name_en' => $objs[$i][2][$j][1],
                        'product_name_tm' => $objs[$i][2][$j][2],
                        'product_name_en' => $objs[$i][2][$j][3],
                        'sort_order' => $j + 1,
                    ]);
                }
            }
    }
}
