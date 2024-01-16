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
use App\Infrastructure\DopClasses\Encrypt;
use SebastianBergmann\Environment\Console;

class ClientQueryService extends ServiceEntityRepository implements ClientQueryServiceInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMClient::class);
    }

    public function getClient(int $id): ?Client
    {
        $client = $this->findOneBy(['id' => $id]) ?? null;
        return $this->hydrateAttempt($client);
    }

    
    public function getClientByEmailAndPassword(string $email, string $password): ?Client
    {
        $client = $this->findOneBy(['email' => $email,'password' => (new Encrypt())->encrypt_decrypt('encrypt', $password)]) ?? null;

        return $this->hydrateAttempt($client);
    }

    private function hydrateAttempt(?ORMClient $ORMClient): ?Client
    {
        if (empty($ORMClient))
        {
            return null;
        }
        $hydrator = new Hydrator();
        return $hydrator->hydrate(Client::class, [
            'id' => $ORMClient->getId(),
            'firstName' => $ORMClient->getFirstName(),
            'lastName' => $ORMClient->getLastName(),
            'birthday' => $ORMClient->getBirthday(),
            'email' => $ORMClient->getEmail(),
            'password' => (new Encrypt())->encrypt_decrypt('decrypt', $ORMClient->getPassword()),
            'patronymic' => $ORMClient->getPatronymic(),
            'photo' => $ORMClient->getPhoto(),
            'telephone' => $ORMClient->getTelephone(),
        ]);
    }
}