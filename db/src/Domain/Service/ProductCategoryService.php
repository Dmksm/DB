<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ProductCategory;

class ProductCategoryService
{
    public function __construct(private readonly  ProductCategoryRepositoryInterface $productCategoryRepository)
    {
    }

    public function AddProductCategory(
        string             $name,
    ): void
    {
        $productCategory = new  ProductCategory(
            $this->productCategoryRepository->getNextId(),
            $name
        );
        $this->productCategoryRepository->AddProductCategory($productCategory);
    }
}
