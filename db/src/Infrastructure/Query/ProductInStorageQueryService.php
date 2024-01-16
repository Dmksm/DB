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

    public function getProductInStorage(int $id): ?ProductInStorage
    {
        return $this->hydrateAttempt($this->findOneBy(['id' => $id]));
    }

    public function getProductInStorageByProductAndStorage(
        int $id_product,
        int $id_storage,
    ): ProductInStorage
    {
        return $this->hydrateAttempt($this->findOneBy(['id_product' => $id_product,'id_storage' => $id_storage]));
    }

    private function hydrateAttempt(?ORMProductInStorage $ORMProductInStorage): ?ProductInStorage
    {
        if (empty($ORMProductInStorage))
        {
            return null;
        }
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ProductInStorage::class, [
            'id' => $ORMProductInStorage->getId(),
            'id_product' => $ORMProductInStorage->getIdProduct(),
            'id_storage' => $ORMProductInStorage->getIdStorage(),
            'count' => $ORMProductInStorage->getCount(),
        ]);
    }
}