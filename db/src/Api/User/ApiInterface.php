<?php
declare(strict_types=1);
namespace App\Api\User;

use App\App\Query\DTO\UserInfo;

interface ApiInterface
{
    public function getUserInfo(int $id): UserInfo;

    public function registerUser(
        string             $firstName,
        string             $lastName,
        \DateTimeImmutable $birthday,
        string             $email,
        string             $password,
        ?string            $patronymic,
        ?string            $photo,
        ?string            $telephone,
        ?string            $position,
    ): void;
}