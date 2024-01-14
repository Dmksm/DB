<?php
declare(strict_types=1);
namespace App\Api\ProductInStorage;

use App\App\Query\DTO\ProductInStorage;

interface ApiProductInStorageInterface
{
    public function getProductInStorage(int $id): ?ProductInStorage;

    public function getProductInStorageByProductAndStorage(
        int $id_product,
        int $id_storage,
    ): ProductInStorage;

    public function addProductInStorage(
        int $id_product,
        int $id_storage,
        int $count,
    ): void;

    public function updateProductInStorage(
        int $id,
        int $id_product,
        int $id_storage,
        int $count,
    ): void;
} 