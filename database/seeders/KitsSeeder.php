<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kits = [
            [
                'name' => 'Старт',
                'pay_outs' => [2000, 1500, 1000, 500, 500],
                'monthly_fee' => 400,
            ],
            [
                'name' => 'Стандарт',
                'pay_outs' => [2000, 1500, 1000, 500, 500, 400, 400, 400, 400, 400],
                'monthly_fee' => 450,
            ],
            [
                'name' => 'Стратос',
                'pay_outs' => array_merge([2000, 1500, 1000, 500, 500], array_fill(0, 15, 400)),
                'monthly_fee' => 500,
            ],
        ];

        foreach ($kits as $k) {
            $price = array_sum($k['pay_outs']);
            DB::table('kits')->updateOrInsert(
                ['name' => $k['name']],
                [
                    'sponsors_count' => count($k['pay_outs']),
                    'monthly_fee' => $k['monthly_fee'],
                    'price' => $price,
                    'pay_outs' => json_encode($k['pay_outs'], JSON_UNESCAPED_UNICODE),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
