<?php
declare(strict_types=1);
namespace App\App\Query;

use App\App\Query\DTO\Product;

interface ProductQueryServiceInterface
{
    public function getProduct(int $id): Product;
}