<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Infrastructure\Repositories\Entity\Storage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Storage>
 *
 * @method Storage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Storage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Storage[]    findAll()
 * @method Storage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StorageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Storage::class);
    }
}
