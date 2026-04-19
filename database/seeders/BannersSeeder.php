<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        DB::table('banners')->insert([
            [
                'id'            => 1,
                'region'        => 'hk',
                'link_type'     => 1,
                'name'          => '夏日清涼大作戰',
                'position_type' => 1,
                'position_id'   => 1,
                'menu_id'       => null,
                'sort'          => 0,
                'image'         => 'https://img.lovepik.com/background/20211021/large/lovepik-the-cool-background-of-the-beach-summer-image_400165541.jpg',
                'link'          => 'consumer/job-categories/1',
                'is_disabled'   => 0,
                'start_time'    => '2024-07-30 09:30:00',
                'end_time'      => '2024-12-31 09:30:00',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 2,
                'region'        => 'hk',
                'link_type'     => 1,
                'name'          => '秋日防蚊大作戰',
                'position_type' => 1,
                'position_id'   => 1,
                'menu_id'       => null,
                'sort'          => 0,
                'image'         => 'https://i.epochtimes.com/assets/uploads/2019/06/mosquito-fumigator-111-600x400.jpg',
                'link'          => 'consumer/event/autumn',
                'is_disabled'   => 0,
                'start_time'    => '2024-07-01 09:30:00',
                'end_time'      => '2024-12-31 09:30:00',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 3,
                'region'        => 'hk',
                'link_type'     => 2,
                'name'          => '冬日進補',
                'position_type' => 1,
                'position_id'   => 2,
                'menu_id'       => null,
                'sort'          => 0,
                'image'         => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR82pPmUDP2-v21haNPyxMOKlux1nvwNNyF5w&s',
                'link'          => 'https://tw.yahoo.com',
                'is_disabled'   => 0,
                'start_time'    => '2024-07-30 09:30:00',
                'end_time'      => '2024-12-31 09:30:00',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 4,
                'region'        => 'hk',
                'link_type'     => 1,
                'name'          => '可愛小物週邊',
                'position_type' => 2,
                'position_id'   => 0,
                'menu_id'       => 1,
                'sort'          => 0,
                'image'         => 'https://mall.iopenmall.tw/website/uploads_product/website_18207/P1820702019053_3_16288139.jpg?hash=82721',
                'link'          => 'consumer/job-categories/2',
                'is_disabled'   => 0,
                'start_time'    => '2024-07-30 09:30:00',
                'end_time'      => '2024-12-31 09:30:00',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 5,
                'region'        => 'hk',
                'link_type'     => 2,
                'name'          => '可愛活動',
                'position_type' => 2,
                'position_id'   => 0,
                'menu_id'       => 1,
                'sort'          => 0,
                'image'         => 'https://mall.iopenmall.tw/website/uploads_product/website_18207/P1820702019053_3_16288139.jpg?hash=82721',
                'link'          => 'https://www.google.com',
                'is_disabled'   => 0,
                'start_time'    => '2024-07-30 09:30:00',
                'end_time'      => '2024-12-31 09:30:00',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ]);
    }
}
