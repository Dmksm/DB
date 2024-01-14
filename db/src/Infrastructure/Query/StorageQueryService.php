<?php
declare(strict_types=1);
namespace App\Infrastructure\Query;

use App\App\Query\DTO\Storage;
use App\App\Query\StorageQueryServiceInterface;
use App\Infrastructure\Hydrator\Hydrator;
use App\Infrastructure\Repositories\Entity\Storage as ORMStorage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;

class StorageQueryService extends ServiceEntityRepository implements StorageQueryServiceInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMStorage::class); 
    }

    public function getStorage(int $id): ?Storage
    {
        return $this->hydrateAttempt($this->findOneBy(['id' => $id]));
    }

    private function hydrateAttempt(ORMStorage $ORMStorage): ?Storage
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(Storage::class, [
            'id' => $ORMStorage->getId(),
            'city' => $ORMStorage->getCity(),
            'street' => $ORMStorage->getStreet(),
            'house' => $ORMStorage->getHouse(),
        ]);
    }
}