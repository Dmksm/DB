<?php
declare(strict_types=1);
namespace App\Domain\Service;

use App\Domain\Entity\ProductCategory;

interface ProductCategoryRepositoryInterface
{
    public function getNextId(): int;
    public function addProductCategory(ProductCategory $productCategory): void;
    public function update(ProductCategory $productCategory): void;
}