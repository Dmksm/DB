<?php
declare(strict_types=1);
namespace App\Domain\Service;

use App\Domain\Entity\ProductPurchase;

interface ProductPurchaseRepositoryInterface
{
    public function getNextId(): int;
    public function add(ProductPurchase $productPurchase): void;
    public function update(ProductPurchase $productPurchase): void;
}