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
        string      $description,
        int         $category,
        int         $cost,
        string|null $photo = null
    ): void
    {
        $product = new  Product(
            $this->productRepository->getNextId(),
            $name,
            $description,
            $category,
            $cost,
            $photo
        );
        $this->productRepository->addProduct($product);
    }

    public function updateProduct(
        int         $id,
        string      $name,
        string      $description,
        int         $category,
        int         $cost,
        string|null $photo = null
    ): void
    {
        $product = new  Product(
            $id,
            $name,
            $description,
            $category,
            $cost,
            $photo
        );
        $this->productRepository->updateProduct($product);
    }
}
