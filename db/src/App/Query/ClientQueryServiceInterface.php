<?php
declare(strict_types=1);
namespace App\App\Query;

use App\App\Query\DTO\Client;

interface ClientQueryServiceInterface
{
    public function getClient(int $id): ?Client;
    public function getClientByEmailAndPassword(string $email, string $password): ?Client;
    
}