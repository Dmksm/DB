<?php
declare(strict_types=1);
namespace App\App\Query;

use App\App\Query\DTO\ProductPurchase;

interface ProductPurchaseQueryServiceInterface
{
    public function getProductPurchase(int $id): ?ProductPurchase;
}