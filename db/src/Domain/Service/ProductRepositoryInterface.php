<?php
declare(strict_types=1);
namespace App\Domain\Service;

use App\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function getNextId(): int;
    public function addProduct(Product $product): void;
    public function updateProduct(Product $product): void;
}