<?php
declare(strict_types=1);
namespace App\Api\Order;

use App\App\Query\DTO\Order;

interface ApiOrderInterface
{
    public function getOrder(int $id): Order;

    public function addOrder(
        int                $id_client,
        float              $sum,
        \DateTimeImmutable $order_date,
        int                $status,
        string             $address,
    ): void;
}