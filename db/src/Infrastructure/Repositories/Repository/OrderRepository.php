<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Domain\Entity\Order;
use App\Infrastructure\Repositories\Entity\Order as ORMOrder;
use App\Domain\Service\OrderRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository implements OrderRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMOrder::class);
    }

    public function getNextId(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT max(s.id)
            FROM App\Infrastructure\Repositories\Entity\Order s'
        );

        return $query->getResult()[0][1] + 1;
    }

    public function add(Order $order): void
    {
        $entityManager = $this->getEntityManager();
        
        $entityManager->persist($this->hydrateOrder($order));
        $entityManager->flush();
    }

    private function hydrateOrder(Order $order): ORMOrder
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ORMOrder::class, [
            'id' => $order->getId(),
            'id_client' => $order->getIdClient(),
            'sum' => $order->getSum(),
            'order_date' => $order->getOrderDate(),
            'status' => $order->getStatus(),
            'address' => $order->getAddress(),
        ]);
    }
}
