<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUsersSeeder::class,
            JobCategoryMenusSeeder::class,
            JobCategoriesSeeder::class,
            JobCategoryRelationsSeeder::class,
            TranslationsJobCategoryMenusSeeder::class,
            TranslationsJobCategoriesSeeder::class,
            RolesSeeder::class,
            FeaturesSeeder::class,
            PermissionsSeeder::class,
            RolePermissionSeeder::class,
            UsersSeeder::class,
            QuotesSeeder::class,
            TagsSeeder::class,
            SmsLogsSeeder::class,
            DistrictsSeeder::class,
            BannersSeeder::class,
            QuestionsSeeder::class,
            CouponSeeder::class,
            TranslationsCouponsSeeder::class,
            TranslationsDistrictsSeeder::class,
        ]);
    }
}
