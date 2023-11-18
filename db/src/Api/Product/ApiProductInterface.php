<?php
declare(strict_types=1);
namespace App\Api\Product;

use App\App\Query\DTO\ProductCategory;

interface ApiProductInterface
{
    public function getProductCategory(int $id): ProductCategory;

    public function AddProductCategory(
        string $name,
    ): void;
}