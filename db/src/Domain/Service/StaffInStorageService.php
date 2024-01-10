<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\StaffInStorage;

class StaffInStorageService
{
    public function __construct(private readonly  StaffInStorageRepositoryInterface $staffInstorageRepository)
    {
    }

    public function addStaffInStorage(
        int      $id_staff,
        int      $id_storage,
    ): void
    {
        $staffInstorage = new StaffInStorage(
            $this->staffInstorageRepository->getNextId(),
            $id_staff,
            $id_storage,
        );
        $this->staffInstorageRepository->addStaffInStorage($staffInstorage);
    }
}
