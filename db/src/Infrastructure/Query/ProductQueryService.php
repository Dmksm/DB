<?php
declare(strict_types=1);
namespace App\Infrastructure\Query;

use App\App\Query\DTO\Product;
use App\App\Query\ProductQueryServiceInterface;
use App\Infrastructure\Hydrator\Hydrator;
use App\Infrastructure\Repositories\Entity\Product as ORMProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;

class ProductQueryService extends ServiceEntityRepository implements ProductQueryServiceInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMProduct::class); 
    }

    public function getProduct(int $id): Product
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
          'SELECT s
          FROM App\Infrastructure\Repositories\Entity\Product s
          WHERE s.id = :id'
        )->setParameters([
            'id' => $id
        ]);
        $ORMProduct = $query->getResult();

        if (empty($ORMProduct))
        {
            throw new QueryException("Product with id $id not found!", 404);
        }
        if (count($ORMProduct) > 1)
        {
            throw new QueryException("Product with id $id are not unique!", 500);
        }

        return $this->hydrateAttempt($ORMProduct[0]);
    }

    public function getProductsByCategory(int $categoryId): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
          'SELECT s
          FROM App\Infrastructure\Repositories\Entity\Product s
          WHERE s.category_id = :categoryId'
        )->setParameters([
            'categoryId' => $categoryId
        ]);
        $ORMProduct = $query->getResult();

        return $ORMProduct;
    }

    public function getAllProducts(): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
          'SELECT s
          FROM App\Infrastructure\Repositories\Entity\Product s'
        );
        $ORMProduct = $query->getResult();
        return $ORMProduct;
    }

    private function hydrateAttempt(ORMProduct $ORMProduct): Product
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(Product::class, [
            'id' => $ORMProduct->getId(),
            'name' => $ORMProduct->getname(),
            'descryption' => $ORMProduct->getDescryption(),
            'category_id' => $ORMProduct->getCategory(),
            'cost' => $ORMProduct->getCost(),
            'photo' => $ORMProduct->getPhoto(),
        ]);
    }
}