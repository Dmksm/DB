<?php
declare(strict_types=1);
namespace App\Domain\Service;

use App\Domain\Entity\Client;

interface ClientRepositoryInterface
{
    public function getNextId(): int;
    public function add(Client $client): void;
    public function update(Client $client): void;
}