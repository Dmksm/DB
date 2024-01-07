<?php
declare(strict_types=1);
namespace App\App\Service;

use App\App\Service\Command\AddStorageCommand;
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
     * @param  AddStorageCommand $command
     * @throws BadRequestHttpException
     */
    public function handle(AddStorageCommand $command): void
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