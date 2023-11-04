<?php

namespace App\Infrastructure\Repository\Service;

use App\Infrastructure\Repository\Entity\StaffInfo;

interface StaffInfoRepositoryInterface
{
    public function findOneById(int $id): ?StaffInfo;

    public function add(StaffInfo $entity);
}