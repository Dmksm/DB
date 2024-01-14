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

    public function getProductPurchase(int $id): ?ProductPurchase
    {
        return $this->hydrateAttempt($this->findOneBy(['id' => $id]));
    }

    private function hydrateAttempt(ORMProductPurchase $ORMProductPurchase): ?ProductPurchase
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