<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Domain\Entity\StaffInfo as DomainStaffInfo;
use App\Infrastructure\Repositories\Entity\StaffInfo as ORMStaffInfo;
use App\Domain\Service\StaffInfoRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function update(DomainStaffInfo $newClient): void
    {
        $entityManager = $this->getEntityManager();
        
        //$product = $entityManager->getRepository(Product::class)->find($id);
        $client = $this->find($newClient->getId());

        if (!$client) {
            throw $this->createNotFoundException(
                'No product found for id '.$newClient->getId()
            );
        }

        $client->setFirstName($newClient->getFirstName());
        $client->setLastName($newClient->getLastName());
        $client->setBirthday($newClient->getBirthday());
        $client->setEmail($newClient->getEmail());
        $client->setPassword($newClient->getPassword());
        $client->setPatronymic($newClient->getPatronymic());
        $client->setPhoto($newClient->getPhoto());
        $client->setTelephone($newClient->getTelephone());
        $client->setPosition($newClient->getPosition() ?? '');
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
            'password' => $staffInfo->getPassword(),
            'patronymic' => $staffInfo->getPatronymic(),
            'photo' => $staffInfo->getPhoto(),
            'telephone' => $staffInfo->getTelephone(),
            'position' => $staffInfo->getPosition()
        ]);
    }
}
