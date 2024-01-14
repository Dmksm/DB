<?php
declare(strict_types=1);
namespace App\App\Query;

use App\App\Query\DTO\ProductInStorage;

interface ProductInStorageQueryServiceInterface
{
    public function getProductInStorage(int $id): ?ProductInStorage;
    public function getProductInStorageByProductAndStorage(
        int $id_product,
        int $id_storage,
    ): ProductInStorage;
}