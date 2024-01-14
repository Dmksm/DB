<?php
declare(strict_types=1);

namespace App\Api\Storage;

use App\App\Query\DTO\Storage;
use App\App\Query\StorageQueryServiceInterface;
use App\App\Service\Command\StorageCommand;
use App\App\Service\AddCommandsHandlers\AddStorageCommandHandler;
use App\Infrastructure\Repositories\Repository\StorageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiStorage implements ApiStorageInterface
{
    public function __construct(
        private readonly ManagerRegistry                $doctrine,
        private readonly ValidatorInterface             $validator,
        private readonly StorageQueryServiceInterface   $storageQueryService, 
    )
    {
    }

    public function getStorage(int $id): ?Storage
    {
        return $this->storageQueryService->getStorage($id);
    }

    public function addStorage(
        string $city,
        string $street,
        string $house,
    ): void
    {
        $storageRepository = new StorageRepository($this->doctrine);  
        $handler = new AddStorageCommandHandler($this->validator, $storageRepository); 
        $command = new StorageCommand(
            0,
            $city,
            $street,
            $house,
        );
        $handler->handle($command);

    }
}