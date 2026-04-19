<?php

namespace App\Services\Common\Providers\Card;

interface CardProvider
{
    public function add(): array;

    public function query(): array;

    public function delete(string $token_id): array;
}
