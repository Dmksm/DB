<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Domain\Entity\StaffInfo as DomainStaffInfo;
use App\Infrastructure\Repositories\Entity\StaffInfo as ORMStaffInfo;
use App\Domain\Service\StaffInfoRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Infrastructure\DopClasses\Encrypt;

/**
 * @extends ServiceEntityRepository<ORMStaffInfo>
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
        parent::__construct($registry, ORMStaffInfo::class);
    }

    public function getNextId(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT max(s.id)
            FROM App\Infrastructure\Repositories\Entity\StaffInfo s'
        );

        return $query->getResult()[0][1] + 1;
    }

    public function add(DomainStaffInfo $staffInfo): void
    {
        $entityManager = $this->getEntityManager();
        
        $entityManager->persist($this->hydrateStaffInfo($staffInfo));
        $entityManager->flush();
    }

    public function update(DomainStaffInfo $newStaff): void
    {
        $entityManager = $this->getEntityManager();
        
        //$product = $entityManager->getRepository(Product::class)->find($id);
        $staff = $this->find($newStaff->getId());

        if (!$staff) {
            throw $this->createNotFoundException(
                'No product found for id '.$newStaff->getId()
            );
        }

        $staff->setFirstName($newStaff->getFirstName());
        $staff->setLastName($newStaff->getLastName());
        $staff->setBirthday($newStaff->getBirthday());
        $staff->setEmail($newStaff->getEmail());
        $staff->setPassword((new Encrypt())->encrypt_decrypt('encrypt', $newStaff->getPassword()));
        $staff->setPatronymic($newStaff->getPatronymic());
        $staff->setPhoto($newStaff->getPhoto());
        $staff->setTelephone($newStaff->getTelephone());
        $staff->setPosition($newStaff->getPosition() ?? '');
        $entityManager->flush();
    }

    private function hydrateStaffInfo(DomainStaffInfo $staffInfo): ORMStaffInfo
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ORMStaffInfo::class, [
            'id' => $staffInfo->getId(),
            'first_name' => $staffInfo->getFirstName(),
            'last_name' => $staffInfo->getLastName(),
            'birthday' => $staffInfo->getBirthday(),
            'email' => $staffInfo->getEmail(),
            'password' => (new Encrypt())->encrypt_decrypt('encrypt', $staffInfo->getPassword()),
            'patronymic' => $staffInfo->getPatronymic(),
            'photo' => $staffInfo->getPhoto(),
            'telephone' => $staffInfo->getTelephone(),
            'position' => $staffInfo->getPosition()
        ]);
    }
}
