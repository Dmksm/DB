<?php
declare(strict_types=1);
namespace App\Api\Client;

use App\App\Query\DTO\Client;

interface ApiClientInterface
{
    public function getClient(int $id): ?Client;
    public function getClientByEmailAndPassword(string $email, string $password): ?Client;

    public function addClient(
        string             $firstName,
        string             $lastName,
        \DateTimeImmutable $birthday,
        string             $email,
        string             $password,
        ?string            $patronymic,
        ?string            $photo,
        ?string            $telephone,
    ): void;

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
    ): void;
}