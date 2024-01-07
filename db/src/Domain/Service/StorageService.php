<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Storage;

class StorageService
{
    public function __construct(private readonly  StorageRepositoryInterface $storageRepository)
    {
    }

    public function addStorage(
        string      $city,
        string      $street,
        string      $house,
    ): void
    {
        $storage = new Storage(
            $this->storageRepository->getNextId(),
            $city,
            $street,
            $house,
        );
        $this->storageRepository->addStorage($storage);
    }
}
