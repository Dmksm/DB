<?php
declare(strict_types=1);
namespace App\Api\User;

use App\App\Query\DTO\UserInfo;
use App\App\Query\StaffInfoQueryServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Api implements ApiInterface
{
    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly ValidatorInterface $validator,
        private readonly StaffInfoQueryServiceInterface $staffInfoQueryService,
    )
    {
    }

    public function getUserInfo(int $id): UserInfo
    {
        return $this->staffInfoQueryService->getUserInfo($id);
    }
}