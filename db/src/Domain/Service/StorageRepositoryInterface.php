<?php
declare(strict_types=1);
namespace App\Domain\Service;

use App\Domain\Entity\Storage;

interface StorageRepositoryInterface
{
    public function getNextId(): int;
    public function addStorage(Storage $storage): void;
}