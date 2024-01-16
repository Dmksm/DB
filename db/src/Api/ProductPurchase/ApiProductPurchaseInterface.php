<?php
declare(strict_types=1);
namespace App\Api\ProductPurchase;

use App\App\Query\DTO\ProductPurchase;

interface ApiProductPurchaseInterface
{
    public function getProductPurchase(int $id): ?ProductPurchase;

    public function addProductPurchase(
        int                $id_product,
        int                $id_order,
        int                $id_storage,
        \DateTimeImmutable $order_date,
        ?\DateTimeImmutable $delivery_date,
        int                $status,
    ): void;

    public function updateProductPurchase(
        int                $id,
        int                $id_product,
        int                $id_order,
        int                $id_storage,
        \DateTimeImmutable $order_date,
        \DateTimeImmutable $delivery_date,
        int                $status,
    ): void;
}