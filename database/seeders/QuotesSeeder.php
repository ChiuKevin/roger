<?php

namespace Database\Seeders;

use App\Models\Quote;
use App\Models\QuotePro;
use Illuminate\Database\Seeder;

class QuotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $qna = '[
                    {
                        "answer": [
                            "a1",
                            "a4"
                        ],
                        "question": "q1"
                    },
                    {
                        "answer": [
                            "a8"
                        ],
                        "question": "q2"
                    },
                    {
                        "answer": [
                            "a3",
                            "a7",
                            "a10"
                        ],
                        "question": "q3"
                    }
                ]';

        $quotes = [
            ['status' => 1, 'job_category_id' => 13, 'user_id' => 76, 'credits' => 10, 'qna' => $qna, 'created_at' => $now, 'updated_at' => $now],
            ['status' => 2, 'job_category_id' => 72, 'user_id' => 19, 'credits' => 10, 'qna' => $qna, 'created_at' => $now, 'updated_at' => $now],
            ['status' => 3, 'job_category_id' => 91, 'user_id' => 68, 'credits' => 10, 'qna' => $qna, 'created_at' => $now, 'updated_at' => $now]
        ];

        Quote::insert($quotes);

        $quote_pros = [
            ['quote_id' => 1, 'pro_id' => 3, 'type' => 1, 'price' => 3000, 'unit' => 1, 'custom_unit' => null, 'is_hired' => false, 'created_at' => $now, 'updated_at' => $now],
            ['quote_id' => 1, 'pro_id' => 6, 'type' => 1, 'price' => 4000, 'unit' => 1, 'custom_unit' => null, 'is_hired' => false, 'created_at' => $now, 'updated_at' => $now],
            ['quote_id' => 1, 'pro_id' => 9, 'type' => 1, 'price' => 5000, 'unit' => 1, 'custom_unit' => null, 'is_hired' => false, 'created_at' => $now, 'updated_at' => $now]
        ];

        QuotePro::insert($quote_pros);
    }
}
