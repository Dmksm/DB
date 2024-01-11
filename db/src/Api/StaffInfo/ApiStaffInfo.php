<?php
declare(strict_types=1);

namespace App\Api\StaffInfo;

use App\App\Query\DTO\StaffInfo;
use App\App\Query\StaffInfoQueryServiceInterface;
use App\App\Service\Command\StaffInfoCommand;
use App\App\Service\AddStaffInfoCommandHandler;
use App\Infrastructure\Repositories\Repository\StaffInfoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiStaffInfo implements ApiStaffInfoInterface
{
    public function __construct(
        private readonly ManagerRegistry                $doctrine,
        private readonly ValidatorInterface             $validator,
        private readonly StaffInfoQueryServiceInterface $staffInfoQueryService,
    )
    {
    }

    public function getStaffInfo(int $id): StaffInfo
    {
        return $this->staffInfoQueryService->getStaffInfo($id);
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
        $staffInfoRepository = new StaffInfoRepository($this->doctrine);
        $handler = new AddStaffInfoCommandHandler($this->validator, $staffInfoRepository);
        $command = new StaffInfoCommand(
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
        $handler->handle($command);
    }
}