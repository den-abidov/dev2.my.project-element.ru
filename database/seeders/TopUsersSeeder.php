<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Сидер «топ-пользователи»: создаёт фиксированную цепочку спонсоров
 * (u1 → u2 → u3 → u4) и задаёт кошельки для P2P-переводов.
 *
 * Зачем: нужны предсказуемые аккаунты и upline для разработки/тестов
 * ReferralSystem/Payment/Subscription.
 *
 * @since 1.0
 * @see \Database\Seeders\DatabaseSeeder  Точка входа, откуда вызывается сидер
 */
class TopUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Твои топ-пользователи (u1 -> u2 -> u3 -> u4)
        $people = [
            [
                'name'   => 'Александр Иванов',
                'nick'   => 'aivan',
                'email'  => 'a.ivanov.element@yandex.ru',
                'wallet' => '4100117203630135',
            ],
            [
                'name'   => 'Татьяна Смирнова',
                'nick'   => 'tsminka7',
                'email'  => 't.smirnova.element@yandex.ru',
                'wallet' => '4100117203553397',
            ],
            [
                'name'   => 'Виталий Попов',
                'nick'   => 'torio',
                'email'  => 'v.popov.element@yandex.ru',
                'wallet' => '100117200190167',
            ],
            [
                'name'   => 'Денис Абидов',
                'nick'   => 'denis',
                'email'  => 'denis.abidov@gmail.com',
                'wallet' => '410012359858615',
            ],
        ];

        $prevId = null;

        foreach ($people as $p) {
            // ищем по email, чтобы сидер был идемпотентным
            $existing = DB::table('users')->where('email', $p['email'])->first();

            $data = [
                'name'              => $p['name'],
                'nick'              => $p['nick'],
                'email'             => $p['email'],
                'wallet'            => $p['wallet'],
                'email_verified_at' => now(),
                'password'          => Hash::make('password'), // dev-пароль
                'updated_at'        => now(),
            ];

            if ($existing) {
                DB::table('users')->where('id', $existing->id)->update($data);
                $userId = $existing->id;
            } else {
                $data['created_at'] = now();
                $userId = DB::table('users')->insertGetId($data);
            }

            // записываем связь в sponsors: текущий -> предыдущий как sponsor
            DB::table('sponsors')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'sponsor_id'            => $prevId,
                    'level'                 => 0,
                    'sponsors_count'        => 0,
                    'sponsor_ids_all'       => null,
                    'sponsor_ids_active'    => null,
                    'sponsor_ids_payee'     => null,
                    'sponsor_ids_payee_all' => null,
                    'payments_status'       => null,
                    'updated_at'            => now(),
                    'created_at'            => now(),
                ]
            );

            $prevId = $userId;
        }
    }
}
