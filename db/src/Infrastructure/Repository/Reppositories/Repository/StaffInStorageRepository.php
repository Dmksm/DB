<?php

namespace App\Repository;

use App\Entity\StaffInStorage;
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

//    /**
//     * @return StaffInStorage[] Returns an array of StaffInStorage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StaffInStorage
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
