<?php
declare(strict_types=1);
namespace App\Api\StaffInStorage;

use App\App\Query\DTO\StaffInStorage;

interface ApiStaffInStorageInterface
{
    public function getStaffInStorage(int $id): ?StaffInStorage;

    public function addStaffInStorage(
        int $id_staff,
        int $id_storage,
    ): void;
} 