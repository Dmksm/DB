<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;


use App\Domain\Entity\ProductPurchase as DomainProductPurchase;
use App\Infrastructure\Repositories\Entity\ProductPurchase as ORMProductPurchase;
use App\Domain\Service\ProductPurchaseRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ORMProductPurchase>
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

    public function add(DomainProductPurchase $productPurchase): void
    {
        $entityManager = $this->getEntityManager();
        
        $entityManager->persist($this->hydrateProductPurchase($productPurchase));
        $entityManager->flush();
    }

    public function update(DomainProductPurchase $newProductPurchase): void
    {
        $entityManager = $this->getEntityManager();
        
        //$product = $entityManager->getRepository(Product::class)->find($id);
        $ProductPurchase = $this->find($newProductPurchase->getId());

        if (!$ProductPurchase) {
            throw $this->createNotFoundException(
                'No product found for id '.$newProductPurchase->getId()
            );
        }

        $ProductPurchase->setIdProduct($newProductPurchase->getIdProduct());
        $ProductPurchase->setIdOrder($newProductPurchase->getIdOrder());
        $ProductPurchase->setIdStorage($newProductPurchase->getIdStorage());
        $ProductPurchase->setOrderDate($newProductPurchase->getOrderDate());
        $ProductPurchase->setDeliveryDate($newProductPurchase->getDeliveryDate());
        $ProductPurchase->setStatus($newProductPurchase->getStatus());
        $entityManager->flush();
    }

    private function hydrateProductPurchase(DomainProductPurchase $productPurchase): ORMProductPurchase
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
