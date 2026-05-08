<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LibType;

class LibTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //$faker = \Faker\Factory::create();
        LibType::create([
            'name_vn' => 'Tất cả',
            'name_en' => 'All',
            'status' => '1',
            'description' => 'all of type',
        ]);
        LibType::create([
            'name_vn' => 'Truyện tranh',
            'name_en' => 'commic',
            'status' => '1',
            'description' => 'commic',
        ]);
        //
    }
}
