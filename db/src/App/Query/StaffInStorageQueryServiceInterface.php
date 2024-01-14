<?php
declare(strict_types=1);
namespace App\App\Query;

use App\App\Query\DTO\StaffInStorage;

interface StaffInStorageQueryServiceInterface
{
    public function getStaffInStorage(int $id): ?StaffInStorage;
}