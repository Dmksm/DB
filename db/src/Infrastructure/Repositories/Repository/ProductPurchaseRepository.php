<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;


use App\Domain\Entity\ProductPurchase;
use App\Infrastructure\Repositories\Entity\ProductPurchase as ORMProductPurchase;
use App\Domain\Service\ProductPurchaseRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductPurchase>
 *
 * @method ProductPurchase|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductPurchase|null findOneBy(array $criteria, array $ProductPurchaseBy = null)
 * @method ProductPurchase[]    findAll()
 * @method ProductPurchase[]    findBy(array $criteria, array $ProductPurchaseBy = null, $limit = null, $offset = null)
 */
class ProductPurchaseRepository extends ServiceEntityRepository implements ProductPurchaseRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMProductPurchase::class);
    }

    public function getNextId(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT max(s.id)
            FROM App\Infrastructure\Repositories\Entity\ProductPurchase s'
        );

        return $query->getResult()[0][1] + 1;
    }

    public function add(ProductPurchase $productPurchase): void
    {
        $entityManager = $this->getEntityManager();
        
        $entityManager->persist($this->hydrateProductPurchase($productPurchase));
        $entityManager->flush();
    }

    private function hydrateProductPurchase(ProductPurchase $productPurchase): ORMProductPurchase
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ORMProductPurchase::class, [
            'id' => $productPurchase->getId(),
            'id_product' => $productPurchase->getIdProduct(),
            'id_order' => $productPurchase->getIdOrder(),
            'id_storage' => $productPurchase->getIdStorage(),
            'order_date' => $productPurchase->getOrderDate(),
            'delivery_date' => $productPurchase->getDeliveryDate(),
            'status' => $productPurchase->getStatus(),
        ]);
    }
}
