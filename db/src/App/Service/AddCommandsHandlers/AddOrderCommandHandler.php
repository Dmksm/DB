<?php
declare(strict_types=1);
namespace App\App\Service\AddCommandsHandlers;

use App\App\Service\Command\OrderCommand;
use App\Domain\Service\OrderRepositoryInterface;
use App\Domain\Service\OrderService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddOrderCommandHandler
{
    private OrderService $orderService;
    
    /**
     * @param ValidatorInterface $validator
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly OrderRepositoryInterface $orderRepository
    )
    {
        $this->orderService = new OrderService($this->orderRepository);
    }

    /**
     * @param  OrderCommand $command
     * @throws BadRequestHttpException
     */
    public function handle(OrderCommand $command): int
    {
        $errors = $this->validator->validate($command);
        if (count($errors) != 0)
        {
            $error = $errors->get(0)->getMessage();
            throw new BadRequestHttpException($error, null, 400);
        }
        
        return $this->orderService->AddOrder(
            $command->getIdClient(),
            $command->getSum(),
            $command->getOrderDate(),
            $command->getStatus(),
            $command->getAddress(),
        );
    }
}