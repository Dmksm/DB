<?php
declare(strict_types=1);
namespace App\Api\Storage;

use App\App\Query\DTO\Storage;

interface ApiStorageInterface
{
    public function getStorage(int $id): ?Storage;

    public function addStorage(
        string $city,
        string $street,
        string $house,
    ): void;
} 