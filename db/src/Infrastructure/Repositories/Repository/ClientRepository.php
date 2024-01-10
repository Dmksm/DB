<?php
declare(strict_types=1);

namespace App\Infrastructure\Repositories\Repository;

use App\Domain\Entity\Client;
use App\Infrastructure\Repositories\Entity\Client as ORMClient;
use App\Domain\Service\ClientRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository implements ClientRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMClient::class);
    }

    public function getNextId(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT max(s.id)
            FROM App\Infrastructure\Repositories\Entity\Client s'
        );

        return $query->getResult()[0][1] + 1;
    }

    public function add(Client $client): void
    {
        $entityManager = $this->getEntityManager();
        
        $entityManager->persist($this->hydrateClient($client));
        $entityManager->flush();
    }

    private function hydrateClient(Client $client): ORMClient
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ORMClient::class, [
            'id' => $client->getId(),
            'first_name' => $client->getFirstName(),
            'last_name' => $client->getLastName(),
            'birthday' => $client->getBirthday(),
            'email' => $client->getEmail(),
            'password' => $client->getPassword(),
            'patronymic' => $client->getPatronymic(),
            'photo' => $client->getPhoto(),
            'telephone' => $client->getTelephone(),
        ]);
    }
}
