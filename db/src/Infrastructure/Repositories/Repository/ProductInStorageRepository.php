<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Infrastructure\Repositories\Entity\ProductInStorage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductInStorage>
 *
 * @method ProductInStorage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductInStorage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductInStorage[]    findAll()
 * @method ProductInStorage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductInStorageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductInStorage::class);
    }
}
