<?php
declare(strict_types=1);
namespace App\App\Query;

use App\App\Query\DTO\UserInfo;

interface StaffInfoQueryServiceInterface
{
    public function getUserInfo(int $id): UserInfo;
}