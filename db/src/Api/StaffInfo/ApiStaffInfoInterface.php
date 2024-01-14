<?php
declare(strict_types=1);
namespace App\Api\StaffInfo;

use App\App\Query\DTO\StaffInfo;

interface ApiStaffInfoInterface
{
    public function getStaffInfo(int $id): ?StaffInfo;
    public function getStaffInfoByEmailAndPassword(string $email,string $password): ?StaffInfo;

    public function addStaffInfo(
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

    public function updateStaffInfo(
        int                $id,
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