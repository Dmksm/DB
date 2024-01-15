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

    public function getOrder(int $id): ?Order
    {
        return $this->hydrateAttempt($this->findOneBy(['id' => $id]));
    }

    public function getAllOrders(): array
    {
        return $this->findAll();
    }

    private function hydrateAttempt(ORMOrder $ORMOrder): ?Order
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