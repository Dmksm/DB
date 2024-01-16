<?php
declare(strict_types=1);
namespace App\App\Query;

use App\App\Query\DTO\Order;

interface OrderQueryServiceInterface
{
    public function getOrder(int $id): ?Order;
    public function getAllOrders(): array;
}