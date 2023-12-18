<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('task_statuses')->insert([
            [
                'name' => 'новый',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'в работе',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'на тестировании',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'завершен',
                'updated_at' => $now,
                'created_at' => $now,
            ],
        ]);
    }
}
