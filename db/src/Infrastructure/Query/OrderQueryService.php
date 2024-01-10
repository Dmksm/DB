<?php
declare(strict_types=1);
namespace App\Infrastructure\Query;

use App\App\Query\DTO\Order;
use App\App\Query\OrderQueryServiceInterface;
use App\Infrastructure\Hydrator\Hydrator;
use App\Infrastructure\Repositories\Entity\Order as ORMOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;

class OrderQueryService extends ServiceEntityRepository implements OrderQueryServiceInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMOrder::class);
    }

    public function getOrder(int $id): Order
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
          'SELECT s
          FROM App\Infrastructure\Repositories\Entity\Order s
          WHERE s.id = :id'
        )->setParameters([
            'id' => $id
        ]);
        $ORMOrder = $query->getResult();

        if (empty($ORMOrder))
        {
            throw new QueryException("User with id $id not found!", 404);
        }
        if (count($ORMOrder) > 1)
        {
            throw new QueryException("User with id $id are not unique!", 500);
        }

        return $this->hydrateAttempt($ORMOrder[0]);
    }

    private function hydrateAttempt(ORMOrder $ORMOrder): Order
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(Order::class, [
            'id' => $ORMOrder->getId(),
            'id_client' => $ORMOrder->getIdClient(),
            'sum' => $ORMOrder->getSum(),
            'order_date' => $ORMOrder->getOrderDate(),
            'status' => $ORMOrder->getStatus(),
            'address' => $ORMOrder->getAddress(),
        ]);
    }
}