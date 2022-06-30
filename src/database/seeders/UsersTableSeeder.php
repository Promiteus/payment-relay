<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(User::TABLE_NAME)->where(User::ID, '=', '300')->delete();
        $id = Uuid::uuid4()->toString();

        DB::table(User::TABLE_NAME)->insert([
            User::ID => $id,
            User::CODE => 1020,
            User::DISABLES => false,
            User::PASSWORD => '$2a$10$11ge20T24VzmMH1mYhcYs.ZJ/lfdJfQF/zxZqANinVqlkWLb4TjT6',
            User::EMAIL => 'dr.romanm@yandex.ru',
            User::REG_DATE => Carbon::now()->toString(),
            User::REMEMBER_TOKEN => null,
            User::VERIFIED_EMAIL => false,
            User::USER_NAME => 'dr.romanm@yandex.ru',
        ]);

        DB::table(User::TABLE_NAME)->where(User::ID, '=', $id)->update([User::ID => '300']);
    }
}
