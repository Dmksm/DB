<?php
declare(strict_types=1);

namespace App\Api\Order;

use App\App\Query\DTO\Order;
use App\App\Query\OrderQueryServiceInterface;
use App\App\Service\Command\AddOrderCommand;
use App\App\Service\AddOrderCommandHandler;
use App\Infrastructure\Repositories\Repository\OrderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiOrder implements ApiOrderInterface
{
    public function __construct(
        private readonly ManagerRegistry                $doctrine,
        private readonly ValidatorInterface             $validator,
        private readonly OrderQueryServiceInterface     $OrderQueryService,
    )
    {
    }

    public function getOrder(int $id): Order
    {
        return $this->OrderQueryService->getOrder($id);
    }

    public function addOrder(
        int                $id_client,
        float              $sum,
        \DateTimeImmutable $order_date,
        int                $status,
        string             $address,
    ): void
    {
        $OrderRepository = new OrderRepository($this->doctrine);
        $handler = new AddOrderCommandHandler($this->validator, $OrderRepository);
        $command = new AddOrderCommand(
            $id_client,
            $sum,
            $order_date,
            $status,
            $address,
        );
        $handler->handle($command);
    }
}