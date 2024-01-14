<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ProductPurchase;

class ProductPurchaseService
{
    public function __construct(private readonly ProductPurchaseRepositoryInterface $productPurchaseRepository)
    {
    }

    public function AddProductPurchase(
        int                $id_product,
        int                $id_order,
        int                $id_storage,
        \DateTimeImmutable $order_date,
        ?\DateTimeImmutable $delivery_date,
        int                $status,
    ): void
    {
        $productPurchase = new ProductPurchase(
            $this->productPurchaseRepository->getNextId(),
            $id_product,
            $id_order,
            $id_storage,
            $order_date,
            $delivery_date,
            $status,
        );
        $this->productPurchaseRepository->add($productPurchase);
    }

    public function updateProductPurchase(
        int                $id,
        int                $id_product,
        int                $id_order,
        int                $id_storage,
        \DateTimeImmutable $order_date,
        \DateTimeImmutable $delivery_date,
        int                $status,
    ): void
    {
        $productPurchase = new ProductPurchase(
            $id,
            $id_product,
            $id_order,
            $id_storage,
            $order_date,
            $delivery_date,
            $status,
        );
        $this->productPurchaseRepository->update($productPurchase);
    }
}
