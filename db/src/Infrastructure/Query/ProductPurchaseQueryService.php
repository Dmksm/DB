<?php
declare(strict_types=1);
namespace App\Infrastructure\Query;

use App\App\Query\DTO\ProductPurchase;
use App\App\Query\ProductPurchaseQueryServiceInterface;
use App\Infrastructure\Hydrator\Hydrator;
use App\Infrastructure\Repositories\Entity\ProductPurchase as ORMProductPurchase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;

class ProductPurchaseQueryService extends ServiceEntityRepository implements ProductPurchaseQueryServiceInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMProductPurchase::class);
    }

    public function getProductPurchase(int $id): ProductPurchase
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
          'SELECT s
          FROM App\Infrastructure\Repositories\Entity\ProductPurchase s
          WHERE s.id = :id'
        )->setParameters([
            'id' => $id
        ]);
        $ORMProductPurchase = $query->getResult();

        if (empty($ORMProductPurchase))
        {
            throw new QueryException("User with id $id not found!", 404);
        }
        if (count($ORMProductPurchase) > 1)
        {
            throw new QueryException("User with id $id are not unique!", 500);
        }

        return $this->hydrateAttempt($ORMProductPurchase[0]);
    }

    private function hydrateAttempt(ORMProductPurchase $ORMProductPurchase): ProductPurchase
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ProductPurchase::class, [
            'id' => $ORMProductPurchase->getId(),
            'id_product' => $ORMProductPurchase->getIdProduct(),
            'id_order' => $ORMProductPurchase->getIdOrder(),
            'id_storage' => $ORMProductPurchase->getIdStorage(),
            'order_date' => $ORMProductPurchase->getOrderDate(),
            'delivery_date' => $ORMProductPurchase->getDeliveryDate(),
            'status' => $ORMProductPurchase->getStatus(),
        ]);
    }
}