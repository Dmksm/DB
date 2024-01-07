<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Domain\Entity\Storage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Infrastructure\Repositories\Entity\Storage as ORMStorage;
use App\Domain\Service\StorageRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;

/**
 * @extends ServiceEntityRepository<Storage>
 *
 * @method Storage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Storage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Storage[]    findAll()
 * @method Storage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StorageRepository extends ServiceEntityRepository implements StorageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMStorage::class);
    }

    public function getNextId(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT max(s.id)
            FROM App\Infrastructure\Repositories\Entity\Storage s'
        );

        return $query->getResult()[0][1] + 1;
    }

    public function addStorage(Storage $storage): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($this->hydrateStorage($storage));
        $entityManager->flush();
    }

    private function hydrateStorage(Storage $storage): ORMStorage
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ORMStorage::class, [
            'id' => $storage->getId(),
            'city' => $storage->getCity(),
            'street' => $storage->getStreet(),
            'house' => $storage->getHouse(),
        ]);
    }
}
