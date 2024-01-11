<?php
declare(strict_types=1);
namespace App\App\Service\AddCommandsHandlers;

use App\App\Service\Command\StorageCommand;
use App\Domain\Service\StorageRepositoryInterface;
use App\Domain\Service\StorageService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddStorageCommandHandler
{
    private StorageService $storageService;
    
    /**
     * @param ValidatorInterface $validator
     * @param StorageRepositoryInterface $storageRepository
     */
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly StorageRepositoryInterface $storageRepository
    )
    {
        $this->storageService = new StorageService($this->storageRepository); 
    }

    /**
     * @param  StorageCommand $command
     * @throws BadRequestHttpException
     */
    public function handle(StorageCommand $command): void
    {
        $errors = $this->validator->validate($command);
        if (count($errors) != 0)
        {
            $error = $errors->get(0)->getMessage();
            throw new BadRequestHttpException($error, null, 400);
        }
        
        $this->storageService->addStorage( 
            $command->getCity(),
            $command->getStreet(),
            $command->getHouse(),
        );
    }
}