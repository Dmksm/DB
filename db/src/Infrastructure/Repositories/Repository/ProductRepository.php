<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Domain\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Infrastructure\Repositories\Entity\Product as ORMProduct;
use App\Domain\Service\ProductRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;
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
            'category_id' => $product->getCategory(),
            'cost' => $product->getCost(),
            'photo' => $product->getPhoto(),
        ]);
    }
}
