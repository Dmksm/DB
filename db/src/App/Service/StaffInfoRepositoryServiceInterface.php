<?php

namespace App\App\Service;

use App\App\Service\DTO\UserInfo;

interface StaffInfoRepositoryServiceInterface
{
    public function getUser(int $id): ?UserInfo;

    public function saveStaffInfo(UserInfo $user): void;
}