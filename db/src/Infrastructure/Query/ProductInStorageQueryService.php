<?php
declare(strict_types=1);
namespace App\Infrastructure\Query;

use App\App\Query\DTO\ProductInStorage;
use App\App\Query\ProductInStorageQueryServiceInterface;
use App\Infrastructure\Hydrator\Hydrator;
use App\Infrastructure\Repositories\Entity\ProductInStorage as ORMProductInStorage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;

class ProductInStorageQueryService extends ServiceEntityRepository implements ProductInStorageQueryServiceInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMProductInStorage::class); 
    }

    public function getProductInStorage(int $id): ProductInStorage
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
          'SELECT s
          FROM App\Infrastructure\Repositories\Entity\ProductInStorage s
          WHERE s.id = :id'
        )->setParameters([
            'id' => $id
        ]);
        $ORMProductInStorage = $query->getResult();

        if (empty($ORMProductInStorage))
        {
            throw new QueryException("ProductInStorage with id $id not found!", 404);
        }
        if (count($ORMProductInStorage) > 1)
        {
            throw new QueryException("ProductInStorage with id $id are not unique!", 500);
        }

        return $this->hydrateAttempt($ORMProductInStorage[0]);
    }

    private function hydrateAttempt(ORMProductInStorage $ORMProductInStorage): ProductInStorage
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ProductInStorage::class, [
            'id' => $ORMProductInStorage->getId(),
            'id_product' => $ORMProductInStorage->getIdProduct(),
            'id_storage' => $ORMProductInStorage->getIdStorage(),
            'count' => $ORMProductInStorage->getCount(),
        ]);
    }
}