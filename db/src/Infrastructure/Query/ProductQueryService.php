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
        return $this->hydrateAttempt($this->findOneBy(['id' => $id]));
    }

    public function getProductsByCategory(int $categoryId): array
    {
        return $this->findBy(['category_id' => $categoryId]);
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
        $ORMProduct = $query->getResult();

        return $ORMProduct;
    }
    public function getAllProducts(): array
    {
        return $this->findAll();
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