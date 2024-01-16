<?php
declare(strict_types=1);
namespace App\Infrastructure\Query;

use App\App\Query\DTO\StaffInfo;
use App\App\Query\StaffInfoQueryServiceInterface;
use App\Infrastructure\Hydrator\Hydrator;
use App\Infrastructure\Repositories\Entity\StaffInfo as ORMStaffInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;
use App\Infrastructure\DopClasses\Encrypt;

class StaffInfoQueryService extends ServiceEntityRepository implements StaffInfoQueryServiceInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMStaffInfo::class);
    }

    public function getStaffInfo(int $id): ?StaffInfo
    {
        return $this->hydrateAttempt($this->findOneBy(['id' => $id]));
    }

    
    public function getStaffInfoByEmailAndPassword(string $email,string $password): ?StaffInfo
    {
        $staff = $this->findOneBy(['email' => $email,'password' => (new Encrypt())->encrypt_decrypt('encrypt', $password)]) ?? null;
        return $this->hydrateAttempt($staff);
    }

    private function hydrateAttempt(?ORMStaffInfo $ORMStaffInfo): ?StaffInfo
    {
        if (empty($ORMStaffInfo)) {
            return null;
        }
        $hydrator = new Hydrator();
        return $hydrator->hydrate(StaffInfo::class, [
            'id' => $ORMStaffInfo->getId(),
            'firstName' => $ORMStaffInfo->getFirstName(),
            'lastName' => $ORMStaffInfo->getLastName(),
            'birthday' => $ORMStaffInfo->getBirthday(),
            'email' => $ORMStaffInfo->getEmail(),
            'password' => (new Encrypt())->encrypt_decrypt('decrypt', $ORMStaffInfo->getPassword()),
            'patronymic' => $ORMStaffInfo->getPatronymic(),
            'photo' => $ORMStaffInfo->getPhoto(),
            'telephone' => $ORMStaffInfo->getTelephone(),
            'position' => $ORMStaffInfo->getPosition()
        ]);
    }
}