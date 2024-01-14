<?php
declare(strict_types=1);
namespace App\Domain\Service;

use App\Domain\Entity\Order;

interface OrderRepositoryInterface
{
    public function getNextId(): int;
    public function add(Order $order): int;
    public function update(Order $order): void;
}