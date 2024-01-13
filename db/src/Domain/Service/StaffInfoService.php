<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\StaffInfo;

class StaffInfoService
{
    public function __construct(private readonly StaffInfoRepositoryInterface $staffInfoRepository)
    {
    }

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
    ): void
    {
        $staffInfo = new StaffInfo(
            $this->staffInfoRepository->getNextId(),
            $firstName,
            $lastName,
            $birthday,
            $email,
            $password,
            $patronymic,
            $photo,
            $telephone,
            $position,
        );
        $this->staffInfoRepository->add($staffInfo);
    }

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
    ): void
    {
        $staffInfo = new StaffInfo(
            $id,
            $firstName,
            $lastName,
            $birthday,
            $email,
            $password,
            $patronymic,
            $photo,
            $telephone,
            $position,
        );
        $this->staffInfoRepository->update($staffInfo);
    }
}
