<?php
declare(strict_types=1);
namespace App\Api\User;

use App\Api\User\DTO\UserInfo;

class Api implements ApiInterface
{
    public function __construct()
    {
    }

    public function getUserInfo(): UserInfo
    {
        return new UserInfo();
    }
}