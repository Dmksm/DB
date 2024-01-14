<?php
declare(strict_types=1);
namespace App\Domain\Service;

use App\Domain\Entity\StaffInfo;

interface StaffInfoRepositoryInterface
{
    public function getNextId(): int;
    public function add(StaffInfo $staffInfo): void;
    public function update(StaffInfo $staffInfo): void;
}