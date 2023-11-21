<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Infrastructure\Repositories\Entity\StaffInStorage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StaffInStorage>
 *
 * @method StaffInStorage|null find($id, $lockMode = null, $lockVersion = null)
 * @method StaffInStorage|null findOneBy(array $criteria, array $orderBy = null)
 * @method StaffInStorage[]    findAll()
 * @method StaffInStorage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaffInStorageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StaffInStorage::class);
    }
}
