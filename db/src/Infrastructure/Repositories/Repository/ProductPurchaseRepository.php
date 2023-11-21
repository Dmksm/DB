<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Infrastructure\Repositories\Entity\ProductPurchase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductPurchase>
 *
 * @method ProductPurchase|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductPurchase|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductPurchase[]    findAll()
 * @method ProductPurchase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductPurchaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductPurchase::class);
    }
}
