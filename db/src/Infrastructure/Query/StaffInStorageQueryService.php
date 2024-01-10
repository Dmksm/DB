<?php
declare(strict_types=1);
namespace App\Infrastructure\Query;

use App\App\Query\DTO\StaffInStorage;
use App\App\Query\StaffInStorageQueryServiceInterface;
use App\Infrastructure\Hydrator\Hydrator;
use App\Infrastructure\Repositories\Entity\StaffInStorage as ORMStaffInStorage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;

class StaffInStorageQueryService extends ServiceEntityRepository implements StaffInStorageQueryServiceInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMStaffInStorage::class); 
    }

    public function getStaffInStorage(int $id): StaffInStorage
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
          'SELECT s
          FROM App\Infrastructure\Repositories\Entity\StaffInStorage s
          WHERE s.id = :id'
        )->setParameters([
            'id' => $id
        ]);
        $ORMStaffInStorage = $query->getResult();

        if (empty($ORMStaffInStorage))
        {
            throw new QueryException("StaffInStorage with id $id not found!", 404);
        }
        if (count($ORMStaffInStorage) > 1)
        {
            throw new QueryException("StaffInStorage with id $id are not unique!", 500);
        }

        return $this->hydrateAttempt($ORMStaffInStorage[0]);
    }

    private function hydrateAttempt(ORMStaffInStorage $ORMStaffInStorage): StaffInStorage
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(StaffInStorage::class, [
            'id' => $ORMStaffInStorage->getId(),
            'id_staff' => $ORMStaffInStorage->getIdStaff(),
            'id_storage' => $ORMStaffInStorage->getIdStorage(),
        ]);
    }
}