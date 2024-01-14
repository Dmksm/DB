<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ProductCategory;

class ProductCategoryService
{
    public function __construct(private readonly  ProductCategoryRepositoryInterface $productCategoryRepository)
    {
    }

    public function addProductCategory(
        string             $name,
    ): void
    {
        $productCategory = new  ProductCategory(
            $this->productCategoryRepository->getNextId(),
            $name
        );
        $this->productCategoryRepository->addProductCategory($productCategory);
    }

    public function update(
        int                $id,
        string             $name,
    ): void
    {
        $productCategory = new ProductCategory(
            $id,
            $name,
        );
        $this->productCategoryRepository->update($productCategory);
    }
}
