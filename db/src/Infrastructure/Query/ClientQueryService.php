<?php
declare(strict_types=1);
namespace App\Infrastructure\Query;

use App\App\Query\DTO\Client;
use App\App\Query\ClientQueryServiceInterface;
use App\Infrastructure\Hydrator\Hydrator;
use App\Infrastructure\Repositories\Entity\Client as ORMClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;

class ClientQueryService extends ServiceEntityRepository implements ClientQueryServiceInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMClient::class);
    }

    public function getClient(int $id): Client
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
          'SELECT s
          FROM App\Infrastructure\Repositories\Entity\Client s
          WHERE s.id = :id'
        )->setParameters([
            'id' => $id
        ]);
        $ORMClient = $query->getResult();

        if (empty($ORMClient))
        {
            throw new QueryException("User with id $id not found!", 404);
        }
        if (count($ORMClient) > 1)
        {
            throw new QueryException("User with id $id are not unique!", 500);
        }

        return $this->hydrateAttempt($ORMClient[0]);
    }

    private function hydrateAttempt(ORMClient $ORMClient): Client
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(Client::class, [
            'id' => $ORMClient->getId(),
            'firstName' => $ORMClient->getFirstName(),
            'lastName' => $ORMClient->getLastName(),
            'birthday' => $ORMClient->getBirthday(),
            'email' => $ORMClient->getEmail(),
            'password' => $ORMClient->getPassword(),
            'patronymic' => $ORMClient->getPatronymic(),
            'photo' => $ORMClient->getPhoto(),
            'telephone' => $ORMClient->getTelephone(),
        ]);
    }
}