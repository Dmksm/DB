<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Order;

class OrderService
{
    public function __construct(private readonly OrderRepositoryInterface $orderRepository)
    {
    }

    public function AddOrder(
        int                $id_client,
        float              $sum,
        \DateTimeImmutable $order_date,
        int                $status,
        string             $address,
    ): int
    {
        $order = new Order(
            $this->orderRepository->getNextId(),
            $id_client,
            $sum,
            $order_date,
            $status,
            $address,
        );
        $this->orderRepository->add($order);
        return $order->getId();
    }

    public function updateOrder(
        int                $id,
        int                $id_client,
        float              $sum,
        \DateTimeImmutable $order_date,
        int                $status,
        string             $address,
    ): void
    {
        $order = new Order(
            $id,
            $id_client,
            $sum,
            $order_date,
            $status,
            $address,
        );
        $this->orderRepository->update($order);
    }
}
