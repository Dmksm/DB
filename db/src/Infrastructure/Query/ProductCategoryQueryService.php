<?php
declare(strict_types=1);
namespace App\Infrastructure\Query;

use App\App\Query\DTO\ProductCategory;
use App\App\Query\ProductCategoryQueryServiceInterface;
use App\Infrastructure\Hydrator\Hydrator;
use App\Infrastructure\Repositories\Entity\ProductCategory as ORMProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;

class ProductCategoryQueryService extends ServiceEntityRepository implements ProductCategoryQueryServiceInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMProductCategory::class);
    }

    public function getProductCategory(int $id): ProductCategory
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
          'SELECT s
          FROM App\Infrastructure\Repositories\Entity\ProductCategory s
          WHERE s.id = :id'
        )->setParameters([
            'id' => $id
        ]);
        $ORMProductCategory = $query->getResult();

        if (empty($ORMProductCategory))
        {
            throw new QueryException("Product category with id $id not found!", 404);
        }
        if (count($ORMProductCategory) > 1)
        {
            throw new QueryException("Product category with id $id are not unique!", 500);
        }

        return $this->hydrateAttempt($ORMProductCategory[0]);
    }

    private function hydrateAttempt(ORMProductCategory $ORMProductCategory): ProductCategory
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ProductCategory::class, [
            'id' => $ORMProductCategory->getId(),
            'name' => $ORMProductCategory->getname()
        ]);
    }
}