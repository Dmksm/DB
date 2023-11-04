<?php

namespace App\App\Service;

use App\App\Service\DTO\UserInfo;
use App\Infrastructure\Repository\Entity\StaffInfo;

interface StaffInfoRepositoryServiceInterface
{
    public function getStaffInfo(int $id): ?StaffInfo;

    public function saveStaffInfo(UserInfo $user): void;
}