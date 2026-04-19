<?php

namespace App\Traits;

trait CardBrandTrait
{
    /**
     * Get client name from route prefix
     *
     * @param string $card_number
     * @return string
     */
    protected function getCardBrand(string $card_number): string
    {
        // 移除信用卡號中的空格和破折號
        $card_number = preg_replace('/\s+|-/', '', $card_number);

        // 確保卡號只有前四位數字
        $first_four_digits = substr($card_number, 0, 4);

        // 定義各信用卡品牌的前四位數字範圍
        $card_brands = [
            'Visa'             => '/^4\d{3}$/',
            'MasterCard'       => '/^5[1-5]\d{2}$/',
            'American Express' => '/^3[47]\d{2}$/',
            'Diners Club'      => '/^3(?:0[0-5]|[68]\d)\d$/',
            'Discover'         => '/^6011$/',
            'JCB'              => '/^(?:2131|1800|35\d{2})$/',
            'UnionPay'         => '/^62\d{2}$/'
        ];

        // 檢查卡號並返回相應的品牌
        foreach ($card_brands as $brand => $pattern) {
            if (preg_match($pattern, $first_four_digits)) {
                return $brand;
            }
        }

        // 如果沒有匹配的品牌，返回未知
        return 'Unknown';
    }
}

