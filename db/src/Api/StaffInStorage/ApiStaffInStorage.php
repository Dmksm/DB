<?php
declare(strict_types=1);

namespace App\Api\StaffInStorage;

use App\App\Query\DTO\StaffInStorage;
use App\App\Query\StaffInStorageQueryServiceInterface;
use App\App\Service\Command\StaffInStorageCommand;
use App\App\Service\AddCommandsHandlers\AddStaffInStorageCommandHandler;
use App\Infrastructure\Repositories\Repository\StaffInStorageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiStaffInStorage implements ApiStaffInStorageInterface
{
    public function __construct(
        private readonly ManagerRegistry                $doctrine,
        private readonly ValidatorInterface             $validator,
        private readonly StaffInStorageQueryServiceInterface   $staffInStorageQueryService, 
    )
    {
    }

    public function getStaffInStorage(int $id): ?StaffInStorage
    {
        return $this->staffInStorageQueryService->getStaffInStorage($id);
    }

    public function addStaffInStorage(
        int $id_staff,
        int $id_storage,
    ): void
    {
        $staffInStorageRepository = new StaffInStorageRepository($this->doctrine);  
        $handler = new AddStaffInStorageCommandHandler($this->validator, $staffInStorageRepository); 
        $command = new StaffInStorageCommand(
            0,
            $id_staff,
            $id_storage,
        );
        $handler->handle($command);

    }
}