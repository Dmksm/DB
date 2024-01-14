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

    public function getProductCategory(int $id): ?ProductCategory
    {
        return $this->hydrateAttempt($this->findOneBy(['id' => $id]));
    }

    private function hydrateAttempt(ORMProductCategory $ORMProductCategory): ?ProductCategory
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ProductCategory::class, [
            'id' => $ORMProductCategory->getId(),
            'name' => $ORMProductCategory->getname()
        ]);
    }
}