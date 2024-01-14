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

    public function getStaffInStorage(int $id): ?StaffInStorage
    {
        return $this->hydrateAttempt($this->findOneBy(['id' => $id]));
    }

    private function hydrateAttempt(ORMStaffInStorage $ORMStaffInStorage): ?StaffInStorage
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(StaffInStorage::class, [
            'id' => $ORMStaffInStorage->getId(),
            'id_staff' => $ORMStaffInStorage->getIdStaff(),
            'id_storage' => $ORMStaffInStorage->getIdStorage(),
        ]);
    }
}