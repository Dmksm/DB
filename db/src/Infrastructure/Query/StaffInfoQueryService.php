<?php
declare(strict_types=1);
namespace App\Infrastructure\Query;

use App\App\Query\DTO\UserInfo;
use App\App\Query\StaffInfoQueryServiceInterface;
use App\Infrastructure\Hydrator\Hydrator;
use App\Infrastructure\Repositories\Entity\StaffInfo as ORMStaffInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;

class StaffInfoQueryService extends ServiceEntityRepository implements StaffInfoQueryServiceInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMStaffInfo::class);
    }

    public function getUserInfo(int $id): UserInfo
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
          'SELECT s
          FROM App\Infrastructure\Repositories\Entity\StaffInfo s
          WHERE s.id = :id'
        )->setParameters([
            'id' => $id
        ]);
        $ORMStaffInfo = $query->getResult();

        if (empty($ORMStaffInfo))
        {
            throw new QueryException("User with id $id not found!", 404);
        }
        if (count($ORMStaffInfo) > 1)
        {
            throw new QueryException("User with id $id are not unique!", 500);
        }

        return $this->hydrateAttempt($ORMStaffInfo[0]);
    }

    private function hydrateAttempt(ORMStaffInfo $ORMStaffInfo): UserInfo
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(UserInfo::class, [
            'id' => $ORMStaffInfo->getId(),
            'firstName' => $ORMStaffInfo->getFirstName(),
            'lastName' => $ORMStaffInfo->getLastName(),
            'birthday' => $ORMStaffInfo->getBirthday(),
            'email' => $ORMStaffInfo->getEmail(),
            'password' => $ORMStaffInfo->getPassword(),
            'patronymic' => $ORMStaffInfo->getPatronymic(),
            'photo' => $ORMStaffInfo->getPhoto(),
            'telephone' => $ORMStaffInfo->getTelephone(),
            'position' => $ORMStaffInfo->getPosition()
        ]);
    }
}