<?php
declare(strict_types=1);
namespace App\Api\Product;

use App\App\Query\DTO\ProductCategory;

interface ApiProductCategoryInterface
{
    public function getProductCategory(int $id): ProductCategory;

    public function addProductCategory(
        string $name,
    ): void;
} 