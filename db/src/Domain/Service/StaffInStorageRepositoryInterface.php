<?php
declare(strict_types=1);
namespace App\Domain\Service;

use App\Domain\Entity\StaffInStorage;

interface StaffInStorageRepositoryInterface
{
    public function getNextId(): int;
    public function addStaffInStorage(StaffInStorage $staffInStorage): void;
}