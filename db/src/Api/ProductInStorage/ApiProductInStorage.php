<?php
declare(strict_types=1);

namespace App\Api\ProductInStorage;

use App\App\Query\DTO\ProductInStorage;
use App\App\Query\ProductInStorageQueryServiceInterface;
use App\App\Service\Command\ProductInStorageCommand;
use App\App\Service\AddCommandsHandlers\AddProductInStorageCommandHandler;
use App\Infrastructure\Repositories\Repository\ProductInStorageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiProductInStorage implements ApiProductInStorageInterface
{
    public function __construct(
        private readonly ManagerRegistry                         $doctrine,
        private readonly ValidatorInterface                      $validator,
        private readonly ProductInStorageQueryServiceInterface   $productInStorageQueryService, 
    )
    {
    }

    public function getProductInStorage(int $id): ProductInStorage
    {
        return $this->productInStorageQueryService->getProductInStorage($id);
    }

    public function addProductInStorage(
        int $id_product,
        int $id_storage,
        int $count,
    ): void
    {
        $productInStorageRepository = new ProductInStorageRepository($this->doctrine);  
        $handler = new AddProductInStorageCommandHandler($this->validator, $productInStorageRepository); 
        $command = new ProductInStorageCommand(
            $id_product,
            $id_storage,
            $count,
        );
        $handler->handle($command);

    }
}