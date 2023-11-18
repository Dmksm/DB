<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Domain\Entity\ProductCategory;
use App\Infrastructure\Repositories\Entity\ProductCategory as ORMProductCategory;
use App\Domain\Service\ProductCategoryRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductCategory>
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

    public function addProductCategory(ProductCategory $productCategory): void
    {
        $entityManager = $this->getEntityManager();
        
        $entityManager->persist($this->hydrateProductCategory($productCategory));
        $entityManager->flush();
    }

    private function hydrateProductCategory(ProductCategory $productCategory): ORMProductCategory
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ORMProductCategory::class, [
            'id' => $productCategory->getId(),
            'name' => $productCategory->getname()
        ]);
    }

//    /**
//     * @return ProductCategory[] Returns an array of ProductCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductCategory
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
