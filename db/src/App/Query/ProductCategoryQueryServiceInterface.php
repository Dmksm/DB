<?php
declare(strict_types=1);
namespace App\App\Query;

use App\App\Query\DTO\ProductCategory;

interface ProductCategoryQueryServiceInterface
{
    public function getProductCategory(int $id): ?ProductCategory;
}