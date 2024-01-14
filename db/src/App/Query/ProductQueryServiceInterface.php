<?php
declare(strict_types=1);
namespace App\App\Query;

use App\App\Query\DTO\Product;

interface ProductQueryServiceInterface
{
    public function getProduct(int $id): ?Product;
    public function getProductsByCategory(int $categoryId): array;
    public function getProductsByIncludingString(string $subString): array;
    public function getAllProducts(): array;

}