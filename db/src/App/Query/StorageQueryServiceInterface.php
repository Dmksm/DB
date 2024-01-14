<?php
declare(strict_types=1);
namespace App\App\Query;

use App\App\Query\DTO\Storage;

interface StorageQueryServiceInterface
{
    public function getStorage(int $id): ?Storage;
}