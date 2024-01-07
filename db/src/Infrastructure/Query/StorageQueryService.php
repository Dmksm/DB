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

    public function getStorage(int $id): Storage
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
          'SELECT s
          FROM App\Infrastructure\Repositories\Entity\Storage s
          WHERE s.id = :id'
        )->setParameters([
            'id' => $id
        ]);
        $ORMStorage = $query->getResult();

        if (empty($ORMStorage))
        {
            throw new QueryException("Storage with id $id not found!", 404);
        }
        if (count($ORMStorage) > 1)
        {
            throw new QueryException("Storage with id $id are not unique!", 500);
        }

        return $this->hydrateAttempt($ORMStorage[0]);
    }

    private function hydrateAttempt(ORMStorage $ORMStorage): Storage
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