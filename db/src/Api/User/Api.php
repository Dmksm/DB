<?php
declare(strict_types=1);

namespace App\Api\User;

use App\App\Query\DTO\UserInfo;
use App\App\Query\StaffInfoQueryServiceInterface;
use App\App\Service\Command\RegisterUserInfoCommand;
use App\App\Service\RegisterUserInfoCommandHandler;
use App\Infrastructure\Repositories\Repository\StaffInfoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Api implements ApiInterface
{
    public function __construct(
        private readonly ManagerRegistry                $doctrine,
        private readonly ValidatorInterface             $validator,
        private readonly StaffInfoQueryServiceInterface $staffInfoQueryService,
    )
    {
    }

    public function getUserInfo(int $id): UserInfo
    {
        return $this->staffInfoQueryService->getUserInfo($id);
    }

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
    ): void
    {
        $staffInfoRepository = new StaffInfoRepository($this->doctrine);
        $handler = new RegisterUserInfoCommandHandler($this->validator, $staffInfoRepository);
        $command = new RegisterUserInfoCommand(
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