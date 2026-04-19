<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['id' => 1, 'username' => 'Sunnie Yu', 'nickname' => 'Sunnie', 'country_code' => '+852', 'phone' => '33345678', 'email' => 'sunnie.yu@vornehk.com', 'password' => bcrypt('12345678'), 'image' => 'https://www.fetchtw.com/images/member/sunnie/sunnie.jpg', 'is_pro' => 0, 'is_disabled' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'username' => 'Walter Lin', 'nickname' => 'Walter', 'country_code' => '+86', 'phone' => '11234567890', 'email' => 'walter.lin@vornehk.com', 'password' => bcrypt('12345678'), 'image' => 'https://www.fetchtw.com/images/member/walter/walter.jpg', 'is_pro' => 0, 'is_disabled' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'username' => 'Carl Chang', 'nickname' => 'Carl', 'country_code' => '+886', 'phone' => '987654321', 'email' => 'carl.chang@vornehk.com', 'password' => bcrypt('12345678'), 'image' => 'https://www.fetchtw.com/images/member/carl/carl.jpg', 'is_pro' => 1, 'is_disabled' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'username' => 'Eddie Yu', 'nickname' => 'Eddie', 'country_code' => '+853', 'phone' => '12345678', 'email' => 'eddie.yu@vornehk.com', 'password' => '', 'image' => 'https://www.fetchtw.com/images/member/eddie/eddie.jpg', 'is_pro' => 0, 'is_disabled' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $countryCodes = ['+852', '+853', '+86', '+886'];
        $now = now();
        $generatedNumbers = [];
        $password = bcrypt('password');
        $values = [];

        for ($i = 1; $i < 100; $i++) {
            $country_code = $countryCodes[array_rand($countryCodes)];

            do {
                $phone = $this->generatePhoneNumber($country_code);
            } while (in_array($phone, $generatedNumbers));

            $generatedNumbers[] = $phone;

            $values[] = [
                'username'     => "user{$i}",
                'nickname'     => "nickname{$i}",
                'country_code' => $country_code,
                'phone'        => $phone,
                'email'        => "user{$i}@gmail.com",
                'password'     => $password,
                'image'        => "https://picsum.photos/200?random={$i}",
                'remark'       => "user{$i} remark",
                'is_pro'       => array_rand([0, 1]),
                'is_disabled'   => array_rand([0, 1]),
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        DB::table('users')->insert($values);

        $user_count = User::count();

        $values = [];

        for ($i = 1; $i <= $user_count; $i++) {
            $values[] = ['user_id' => $i];
        }

        DB::table('user_notification_settings')->insert($values);
    }

    private function generatePhoneNumber($country_code)
    {
        switch ($country_code) {
            case '+852':
            case '+853':
                return rand(10000000, 99999999);
            case '+86':
                return '1' . rand(1000000000, 9999999999);
            case '+886':
                return '9' . rand(10000000, 99999999);
        }
    }
}
