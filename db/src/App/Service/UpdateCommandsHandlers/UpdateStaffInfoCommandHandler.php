<?php
declare(strict_types=1);
namespace App\App\Service\UpdateCommandsHandlers;

use App\App\Service\Command\StaffInfoCommand;
use App\Domain\Service\StaffInfoRepositoryInterface;
use App\Domain\Service\StaffInfoService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateStaffInfoCommandHandler
{
    private StaffInfoService $staffInfoService;
    
    /**
     * @param ValidatorInterface $validator
     * @param StaffInfoRepositoryInterface $staffInfoRepository
     */
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly StaffInfoRepositoryInterface $staffInfoRepository
    )
    {
        $this->staffInfoService = new StaffInfoService($this->staffInfoRepository);
    }

    /**
     * @param StaffInfoCommand $command
     * @throws BadRequestHttpException
     */
    public function handle(StaffInfoCommand $command): void
    {
        $errors = $this->validator->validate($command);
        if (count($errors) != 0)
        {
            $error = $errors->get(0)->getMessage();
            throw new BadRequestHttpException($error, null, 400);
        }
        
        $this->staffInfoService->updateStaffInfo(
            $command->getId(),
            $command->getFirstName(),
            $command->getLastName(),
            $command->getBirthday(),
            $command->getEmail(),
            $command->getPassword(),
            $command->getPatronymic(),
            $command->getPhoto(),
            $command->getTelephone(),
            $command->getPosition(),
        );
    }
}