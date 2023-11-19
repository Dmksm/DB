<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Domain\Entity\Product;
use App\Infrastructure\Repositories\Entity\Product as ORMProduct;
use App\Domain\Service\ProductRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMProduct::class);
    }

    public function getNextId(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT max(s.id)
            FROM App\Infrastructure\Repositories\Entity\Product s'
        );

        return $query->getResult()[0][1] + 1;
    }

    public function addProduct(Product $product): void
    {
        $entityManager = $this->getEntityManager();
        
        $entityManager->persist($this->hydrateProduct($product));
        $entityManager->flush();
    }

    private function hydrateProduct(Product $product): ORMProduct
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ORMProduct::class, [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'descryption' => $product->getDescryption(),
            'category' => $product->getCategory(),
            'cost' => $product->getCost(),
            'photo' => $product->getPhoto(),
        ]);
    }

//    /**
//     * @return Product[] Returns an array of Product objects
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

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
