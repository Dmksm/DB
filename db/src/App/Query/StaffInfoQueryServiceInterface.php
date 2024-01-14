<?php
declare(strict_types=1);
namespace App\App\Query;

use App\App\Query\DTO\StaffInfo;

interface StaffInfoQueryServiceInterface
{
    public function getStaffInfo(int $id): ?StaffInfo;
    
    public function getStaffInfoByEmailAndPassword(string $email,string $password): ?StaffInfo;
}