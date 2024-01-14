<?php
declare(strict_types=1);
namespace App\App\Service\AddCommandsHandlers;

use App\App\Service\Command\StaffInStorageCommand;
use App\Domain\Service\StaffInStorageRepositoryInterface;
use App\Domain\Service\StaffInStorageService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddStaffInStorageCommandHandler
{
    private StaffInStorageService $staffInStorageService;
    
    /**
     * @param ValidatorInterface $validator
     * @param StaffInStorageRepositoryInterface $staffInStorageRepository
     */
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly StaffInStorageRepositoryInterface $staffInStorageRepository
    )
    {
        $this->staffInStorageService = new StaffInStorageService($this->staffInStorageRepository); 
    }

    /**
     * @param  StaffInStorageCommand $command
     * @throws BadRequestHttpException
     */
    public function handle(StaffInStorageCommand $command): void
    {
        $errors = $this->validator->validate($command);
        if (count($errors) != 0)
        {
            $error = $errors->get(0)->getMessage();
            throw new BadRequestHttpException($error, null, 400);
        }
        
        $this->staffInStorageService->addStaffInStorage( 
            $command->getIdStaff(),
            $command->getIdStorage(),
        );
    }
}