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
                    ['Biznes ýöretmek', null, 'Biznes ýöretmek', null],
                    ['Buhgalteriýa', null, 'Buhgalteriýa', null],
                    ['Marketing', null, 'Marketing', null],
                    ['Önümçilik', null, 'Önümçilik', null],
                    ['Söwda we satuw', null, 'Söwda we satuw', null],
                    ['Söwda we syýahatçylyk', null, 'Söwda we syýahatçylyk', null],
                ]],
                ['Dizaýn', null, [
                    ['3D we animasiýa', null, '3D we animasiýa', null],
                    ['Adobe programmalry', null, 'Adobe programmalry', null],
                    ['Arhitektura we inženerçilik', null, 'Arhitektura we inženerçilik', null],
                    ['Web dizaýn', null, 'Web dizaýn', null],
                ]],
                ['Informatika we internet', null, [
                    ['Informatika', null, 'Informatika', null],
                    ['Internet we tor howpsuzlygy', null, 'Internet we tor howpsuzlygy', null],
                    ['Kompýuter programmalary', null, 'Kompýuter programmalary', null],
                    ['Täze tehnologiýalar', null, 'Täze tehnologiýalar', null],
                ]],
                ['Programmirleme', null, [
                    ['Mobil priloženiýalary düzmek', null, 'Mobil priloženiýalary düzmek', null],
                    ['Oýunlary düzmek', null, 'Oýunlary düzmek', null],
                    ['Programmirleme dilleri', null, 'Programmirleme dilleri', null],
                    ['Robotlar tehnologiýasy', null, 'Robotlar tehnologiýasy', null],
                    ['Web programmirleme', null, 'Web programmirleme', null],
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
                        'course_name_tm' => $objs[$i][2][$j][2],
                        'course_name_en' => $objs[$i][2][$j][3],
                        'sort_order' => $j + 1,
                    ]);
                }
            }
        }
    }
}
