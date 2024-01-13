<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Domain\Entity\ProductCategory as DomainProductCategory;
use App\Infrastructure\Repositories\Entity\ProductCategory as ORMProductCategory;
use App\Domain\Service\ProductCategoryRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ORMProductCategory>
 *
 * @method ProductCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCategory[]    findAll()
 * @method ProductCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCategoryRepository extends ServiceEntityRepository implements ProductCategoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMProductCategory::class);
    }

    public function getNextId(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT max(s.id)
            FROM App\Infrastructure\Repositories\Entity\ProductCategory s'
        );

        return $query->getResult()[0][1] + 1;
    }

    public function addProductCategory(DomainProductCategory $productCategory): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($this->hydrateProductCategory($productCategory));
        $entityManager->flush();
    }

    public function update(DomainProductCategory $newOrder): void
    {
        $entityManager = $this->getEntityManager();
        
        $order = $this->find($newOrder->getId());

        if (!$order) {
            throw $this->createNotFoundException(
                'No product found for id '.$newOrder->getId()
            );
        }

        $order->setname($newOrder->getname());

        $entityManager->flush();
    }

    private function hydrateProductCategory(DomainProductCategory $productCategory): ORMProductCategory
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ORMProductCategory::class, [
            'id' => $productCategory->getId(),
            'name' => $productCategory->getname()
        ]);
    }
}
