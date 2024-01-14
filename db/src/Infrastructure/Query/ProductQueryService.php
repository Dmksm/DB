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

    public function getProduct(int $id): ?Product
    {
        return $this->hydrateProduct($this->findOneBy(['id' => $id]));
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
        $ORMProducts = $query->getResult();
        $products = [];
        foreach ($ORMProducts as $ORMProduct)
        {
            $products[] = $ORMProduct;
        }
        return $products;
    }

    
    public function getProductsByIncludingString(string $subString): array
    {
        $subString = '%' . $subString .'%';
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
          'SELECT s
          FROM App\Infrastructure\Repositories\Entity\Product s
          WHERE s.name LIKE :subString'
        )->setParameters([
            'subString' => $subString
        ]);
        $ORMProducts = $query->getResult();
        $products = [];
        foreach ($ORMProducts as $ORMProduct)
        {
            $products[] = $ORMProduct;
        }
        return $products;
    }
    public function getAllProducts(): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
          'SELECT s
          FROM App\Infrastructure\Repositories\Entity\Product s'
        );
        $ORMProducts = $query->getResult();
        $products = [];
        foreach ($ORMProducts as $ORMProduct)
        {
            $products[] = $ORMProduct;
        }
        return $products;
    }

    private function hydrateProduct(ORMProduct $ORMProduct): ?Product
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