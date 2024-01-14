<?php
declare(strict_types=1);
namespace App\Domain\Service;

use App\Domain\Entity\ProductInStorage;

interface ProductInStorageRepositoryInterface
{
    public function getNextId(): int;
    public function addProductInStorage(ProductInStorage $productInstorage): void;
    public function updateProductInStorage(ProductInStorage $productInstorage): void;
}