<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationsCouponsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $translations = [
            [
                'table' => 'coupons',
                'column' => 'description',
                'translation' => '{"1":{"zh_hk":"2024夏季折扣券","zh_mo":"2024夏季折扣券","zh_tw":"2024夏季折扣券","en":"2024 Summer Discount Coupon"},"2":{"zh_hk":"2024歡迎折扣券","zh_mo":"2024歡迎折扣券","zh_tw":"2024歡迎折扣券","en":"2024 Welcome Discount Coupon"},"3":{"zh_hk":"2024新年折扣券","zh_mo":"2024新年折扣券","zh_tw":"2024新年折扣券","en":"2024 New Year Discount Coupon"},"4":{"zh_hk":"2024黑色星期五折扣券","zh_mo":"2024黑色星期五折扣券","zh_tw":"2024黑色星期五折扣券","en":"2024 Black Friday Discount Coupon"},"5":{"zh_hk":"2024春季折扣券","zh_mo":"2024春季折扣券","zh_tw":"2024春季折扣券","en":"2024 Spring Discount Coupon"},"6":{"zh_hk":"2024秋季折扣券","zh_mo":"2024秋季折扣券","zh_tw":"2024秋季折扣券","en":"2024 Autumn Discount Coupon"}}'
            ],
        ];

        $sql = "INSERT INTO translations (`table`, `column`, `translation`) VALUES";

        $values = [];

        foreach ($translations as $translation) {
            $json = addslashes($translation['translation']);
            $json = preg_replace('/\s+/', ' ', $json);

            $values[] = "('{$translation['table']}', '{$translation['column']}', '{$json}')";
        }

        $sql .= implode(', ', $values);

        DB::insert($sql);
    }
}
