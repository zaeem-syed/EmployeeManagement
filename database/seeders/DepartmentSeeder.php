<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            [
                'name' => 'Engineering',
                'code' => 'ENG',
                'description' => 'Responsible for all technical development and systems.',
            ],
            [
                'name' => 'Marketing',
                'code' => 'MKT',
                'description' => 'Focuses on advertising, promotions, and market research.',
            ],
            [
                'name' => 'Human Resources',
                'code' => 'HR',
                'description' => 'Handles recruitment, employee relations, and organizational development.',
            ]
        ]);
    }
}
