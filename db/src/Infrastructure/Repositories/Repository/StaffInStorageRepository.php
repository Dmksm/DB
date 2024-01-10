<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Domain\Entity\StaffInStorage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Infrastructure\Repositories\Entity\StaffInStorage as ORMStaffInStorage;
use App\Domain\Service\StaffInStorageRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;

/**
 * @extends ServiceEntityRepository<StaffInStorage>
 *
 * @method StaffInStorage|null find($id, $lockMode = null, $lockVersion = null)
 * @method StaffInStorage|null findOneBy(array $criteria, array $orderBy = null)
 * @method StaffInStorage[]    findAll()
 * @method StaffInStorage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaffInStorageRepository extends ServiceEntityRepository implements StaffInStorageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMStaffInStorage::class);
    }
    public function getNextId(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT max(s.id)
            FROM App\Infrastructure\Repositories\Entity\StaffInStorage s'
        );

        return $query->getResult()[0][1] + 1;
    }

    public function addStaffInStorage(StaffInStorage $staffInStorage): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($this->hydrateStaffInStorage($staffInStorage));
        $entityManager->flush();
    }

    private function hydrateStaffInStorage(StaffInStorage $staffInStorage): ORMStaffInStorage
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ORMStaffInStorage::class, [
            'id' => $staffInStorage->getId(),
            'id_staff' => $staffInStorage->getIdStaff(),
            'id_storage' => $staffInStorage->getIdStorage(),
        ]);
    }
}
