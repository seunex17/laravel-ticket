<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Agent One',
            ],
            [
                'name' => 'Agent Two',
            ],
            [
                'name' => 'Agent Three',
            ],
        ];

        DB::table('agents')->insert($data);
    }
}
