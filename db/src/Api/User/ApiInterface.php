<?php
declare(strict_types=1);
namespace App\Api\User;

use App\App\Query\DTO\UserInfo;

interface ApiInterface
{
    public function getUserInfo(int $id): UserInfo;
}