<?php
declare(strict_types=1);
namespace App\Api\User;

use App\Api\User\DTO\UserInfo;

interface ApiInterface
{
    public function getUserInfo(): UserInfo;
}