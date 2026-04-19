<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('coupons')->insert([
            [
                'coupon_code'         => 'SUMMER2024',
                'discount_type'       => 'percentage',
                'discount_value'      => 20,
                'min_purchase_amount' => 100.00,
                'valid_from'          => '2024-08-01',
                'valid_until'         => '2024-08-31',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'coupon_code'         => 'WELCOME2024',
                'discount_type'       => 'fixed',
                'discount_value'      => 50.00,
                'min_purchase_amount' => 200.00,
                'valid_from'          => '2024-01-01',
                'valid_until'         => '2024-12-31',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'coupon_code'         => 'NEWYEAR2024',
                'discount_type'       => 'percentage',
                'discount_value'      => 10,
                'min_purchase_amount' => 50.00,
                'valid_from'          => '2024-12-20',
                'valid_until'         => '2024-12-31',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'coupon_code'         => 'BLACKFRIDAY2024',
                'discount_type'       => 'fixed',
                'discount_value'      => 100.00,
                'min_purchase_amount' => 500.00,
                'valid_from'          => '2024-11-25',
                'valid_until'         => '2024-11-29',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'coupon_code'         => 'SPRING2024',
                'discount_type'       => 'percentage',
                'discount_value'      => 15,
                'min_purchase_amount' => 150.00,
                'valid_from'          => '2024-03-01',
                'valid_until'         => '2024-03-31',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'coupon_code'         => 'AUTUMN2024',
                'discount_type'       => 'fixed',
                'discount_value'      => 30.00,
                'min_purchase_amount' => 100.00,
                'valid_from'          => '2024-09-01',
                'valid_until'         => '2024-09-30',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
        ]);

        DB::table('coupon_job_categories')->insert([
            // SUMMER2024
            ['coupon_id' => 1, 'job_category_id' => 1],
            ['coupon_id' => 1, 'job_category_id' => 2],

            // WELCOME2024
            ['coupon_id' => 2, 'job_category_id' => 3],
            ['coupon_id' => 2, 'job_category_id' => 4],

            // NEWYEAR2024
            ['coupon_id' => 3, 'job_category_id' => 1],
            ['coupon_id' => 3, 'job_category_id' => 5],

            // BLACKFRIDAY2024
            ['coupon_id' => 4, 'job_category_id' => 2],
            ['coupon_id' => 4, 'job_category_id' => 6],

            // SPRING2024
            ['coupon_id' => 5, 'job_category_id' => 3],
            ['coupon_id' => 5, 'job_category_id' => 4],

            // AUTUMN2024
            ['coupon_id' => 6, 'job_category_id' => 5],
            ['coupon_id' => 6, 'job_category_id' => 6],
        ]);

        DB::table('user_coupons')->insert([
            [
                'user_id'     => 1,
                'coupon_id'   => 1, // SUMMER2024
                'redeemed_at' => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'user_id'     => 1,
                'coupon_id'   => 2, // WELCOME2024
                'redeemed_at' => '2024-02-01 12:34:56',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'user_id'     => 1,
                'coupon_id'   => 3, // NEWYEAR2024
                'redeemed_at' => '2024-12-20 10:00:00',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'user_id'     => 1,
                'coupon_id'   => 4, // BLACKFRIDAY2024
                'redeemed_at' => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'user_id'     => 1,
                'coupon_id'   => 5, // SPRING2024
                'redeemed_at' => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'user_id'     => 1,
                'coupon_id'   => 6, // AUTUMN2024
                'redeemed_at' => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'user_id'     => 4,
                'coupon_id'   => 1, // SUMMER2024
                'redeemed_at' => '2024-08-15 14:22:30', // 已使用
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'user_id'     => 5,
                'coupon_id'   => 3, // NEWYEAR2024
                'redeemed_at' => '2024-12-25 18:45:00', // 已使用
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
