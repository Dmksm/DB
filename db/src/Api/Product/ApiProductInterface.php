<?php
declare(strict_types=1);
namespace App\Api\Product;

use App\App\Query\DTO\Product;

interface ApiProductInterface
{
    public function getProduct(int $id): Product;

    public function addProduct(
        string $name,
        string $descryption,
        int $category,
        int $cost,
        string|null $photo = null
    ): void;
} 