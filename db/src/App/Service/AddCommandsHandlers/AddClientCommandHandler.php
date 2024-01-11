<?php
declare(strict_types=1);
namespace App\App\Service\AddCommandsHandlers;

use App\App\Service\Command\ClientCommand;
use App\Domain\Service\ClientRepositoryInterface;
use App\Domain\Service\ClientService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddClientCommandHandler
{
    private ClientService $clientService;
    
    /**
     * @param ValidatorInterface $validator
     * @param ClientRepositoryInterface $clientRepository
     */
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly ClientRepositoryInterface $clientRepository
    )
    {
        $this->clientService = new ClientService($this->clientRepository);
    }

    /**
     * @param ClientCommand $command
     * @throws BadRequestHttpException
     */
    public function handle(ClientCommand $command): void
    {
        $errors = $this->validator->validate($command);
        if (count($errors) != 0)
        {
            $error = $errors->get(0)->getMessage();
            throw new BadRequestHttpException($error, null, 400);
        }
        
        $this->clientService->addClient(
            $command->getFirstName(),
            $command->getLastName(),
            $command->getBirthday(),
            $command->getEmail(),
            $command->getPassword(),
            $command->getPatronymic(),
            $command->getPhoto(),
            $command->getTelephone(),
        );
    }
}