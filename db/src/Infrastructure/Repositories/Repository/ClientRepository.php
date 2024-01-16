<?php
declare(strict_types=1);

namespace App\Infrastructure\Repositories\Repository;

use App\Domain\Entity\Client as DomainClient;
use App\Infrastructure\DopClasses\Encrypt;
use App\Infrastructure\Repositories\Entity\Client as ORMClient;
use App\Domain\Service\ClientRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ORMClient>
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

    public function add(DomainClient $client): void
    {
        $entityManager = $this->getEntityManager();
        
        $entityManager->persist($this->hydrateClient($client));
        $entityManager->flush();
    }

    public function update(DomainClient $newClient): void
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
        $client->setPassword((new Encrypt())->encrypt_decrypt('encrypt', $newClient->getPassword()));
        $client->setPatronymic($newClient->getPatronymic());
        $client->setPhoto($newClient->getPhoto());
        $client->setTelephone($newClient->getTelephone());
        $entityManager->flush();
    }

    private function hydrateClient(DomainClient $client): ORMClient
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ORMClient::class, [
            'id' => $client->getId(),
            'first_name' => $client->getFirstName(),
            'last_name' => $client->getLastName(),
            'birthday' => $client->getBirthday(),
            'email' => $client->getEmail(),
            'password' => (new Encrypt())->encrypt_decrypt('encrypt', $client->getPassword()),
            'patronymic' => $client->getPatronymic(),
            'photo' => $client->getPhoto(),
            'telephone' => $client->getTelephone(),
        ]);
    }
}
