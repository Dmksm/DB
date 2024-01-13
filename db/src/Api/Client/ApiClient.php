<?php
declare(strict_types=1);

namespace App\Api\Client;

use App\App\Query\DTO\Client;
use App\App\Query\ClientQueryServiceInterface;
use App\App\Service\Command\RegisterClientCommand;
use App\App\Service\RegisterClientCommandHandler;
use App\Infrastructure\Repositories\Repository\ClientRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiClient implements ApiClientInterface
{
    public function __construct(
        private readonly ManagerRegistry                $doctrine,
        private readonly ValidatorInterface             $validator,
        private readonly ClientQueryServiceInterface    $clientQueryService,
    )
    {
    }

    public function getClient(int $id): Client
    {
        return $this->clientQueryService->getClient($id);
    }

    public function addClient(
        string             $firstName,
        string             $lastName,
        \DateTimeImmutable $birthday,
        string             $email,
        string             $password,
        ?string            $patronymic,
        ?string            $photo,
        ?string            $telephone,
    ): void
    {
        $clientRepository = new ClientRepository($this->doctrine);
        $handler = new RegisterClientCommandHandler($this->validator, $clientRepository);
        $command = new RegisterClientCommand(
            $firstName,
            $lastName,
            $birthday,
            $email,
            $password,
            $patronymic,
            $photo,
            $telephone,
        );
        $handler->handle($command);
    }
}