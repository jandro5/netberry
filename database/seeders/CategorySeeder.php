<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'PHP',
            'Javascript',
            'CSS',
        ];

        foreach ($data as $d) {
            DB::table('categories')->insert([
                'name' => $d
            ]);
        }
    }
}
