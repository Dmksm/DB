<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Client;

class ClientService
{
    public function __construct(private readonly ClientRepositoryInterface $clientRepository)
    {
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
        $client = new Client(
            $this->clientRepository->getNextId(),
            $firstName,
            $lastName,
            $birthday,
            $email,
            $password,
            $patronymic,
            $photo,
            $telephone,
        );
        $this->clientRepository->add($client);
    }

    public function updateClient(
        int                $id,
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
        $client = new Client(
            $id,
            $firstName,
            $lastName,
            $birthday,
            $email,
            $password,
            $patronymic,
            $photo,
            $telephone,
        );
        $this->clientRepository->update($client);
    }
}
