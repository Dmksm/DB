<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Product;

class ProductService
{
    public function __construct(private readonly  ProductRepositoryInterface $productRepository)
    {
    }

    public function addProduct(
        string      $name,
        string      $descryption,
        int         $category,
        int         $cost,
        string|null $photo = null
    ): void
    {
        $product = new  Product(
            $this->productRepository->getNextId(),
            $name,
            $descryption,
            $category,
            $cost,
            $photo
        );
        $this->productRepository->addProduct($product);
    }

    public function updateProduct(
        int         $id,
        string      $name,
        string      $descryption,
        int         $category,
        int         $cost,
        string|null $photo = null
    ): void
    {
        $product = new  Product(
            $id,
            $name,
            $descryption,
            $category,
            $cost,
            $photo
        );
        $this->productRepository->updateProduct($product);
    }
}
