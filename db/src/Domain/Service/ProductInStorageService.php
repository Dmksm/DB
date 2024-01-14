<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ProductInStorage;

class ProductInStorageService
{
    public function __construct(private readonly  ProductInStorageRepositoryInterface $productInstorageRepository)
    {
    }

    public function addProductInStorage(
        int      $id_product,
        int      $id_storage,
        int      $count,
    ): void
    {
        $productInstorage = new ProductInStorage(
            $this->productInstorageRepository->getNextId(),
            $id_product,
            $id_storage,
            $count,
        );
        $this->productInstorageRepository->addProductInStorage($productInstorage);
    }

    public function updateProductInStorage(
        int      $id,
        int      $id_product,
        int      $id_storage,
        int      $count,
    ): void
    {
        $productInstorage = new ProductInStorage(
            $id,
            $id_product,
            $id_storage,
            $count,
        );
        $this->productInstorageRepository->updateProductInStorage($productInstorage);
    }
}
