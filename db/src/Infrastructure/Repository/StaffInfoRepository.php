<?php
declare(strict_types=1);
namespace App\Infrastructure\Repository;

use App\Infrastructure\Repository\Entity\StaffInfo;
use App\Infrastructure\Repository\Service\StaffInfoRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StaffInfo>
 *
 * @method StaffInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method StaffInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method StaffInfo[]    findAll()
 * @method StaffInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaffInfoRepository extends ServiceEntityRepository implements StaffInfoRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StaffInfo::class);
    }

    public function findOneById(int $id): ?StaffInfo
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function add(StaffInfo $entity)
    {
        $this->doctrine->getManager()->persist($entity);
        $this->doctrine->getManager()->flush();
    }

//    /**
//     * @return StaffInfo[] Returns an array of StaffInfo objects
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

//    public function findOneBySomeField($value): ?StaffInfo
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
