<?php
declare(strict_types=1);

namespace App\Api\ProductPurchase;

use App\App\Query\DTO\ProductPurchase;
use App\App\Query\ProductPurchaseQueryServiceInterface;
use App\App\Service\Command\ProductPurchaseCommand;
use App\App\Service\AddCommandsHandlers\AddProductPurchaseCommandHandler;
use App\App\Service\UpdateCommandsHandlers\UpdateProductPurchaseCommandHandler;
use App\Infrastructure\Repositories\Repository\ProductPurchaseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiProductPurchase implements ApiProductPurchaseInterface
{
    public function __construct(
        private readonly ManagerRegistry                        $doctrine,
        private readonly ValidatorInterface                     $validator,
        private readonly ProductPurchaseQueryServiceInterface   $productPurchaseQueryService,
    )
    {
    }

    public function getProductPurchase(int $id): ?ProductPurchase
    {
        return $this->productPurchaseQueryService->getProductPurchase($id);
    }

    public function addProductPurchase(
        int                $id_product,
        int                $id_order,
        int                $id_storage,
        \DateTimeImmutable $order_date,
        ?\DateTimeImmutable $delivery_date,
        int                $status,
    ): void
    {
        $ProductPurchaseRepository = new ProductPurchaseRepository($this->doctrine);
        $handler = new AddProductPurchaseCommandHandler($this->validator, $ProductPurchaseRepository);
        $command = new ProductPurchaseCommand(
            0,
            $id_product,
            $id_order,
            $id_storage,
            $order_date,
            $delivery_date,
            $status,
        );
        $handler->handle($command);
    }

    public function updateProductPurchase(
        int                $id,
        int                $id_product,
        int                $id_order,
        int                $id_storage,
        \DateTimeImmutable $order_date,
        \DateTimeImmutable $delivery_date,
        int                $status,
    ): void
    {
        $ProductPurchaseRepository = new ProductPurchaseRepository($this->doctrine);
        $handler = new UpdateProductPurchaseCommandHandler($this->validator, $ProductPurchaseRepository);
        $command = new ProductPurchaseCommand(
            $id,
            $id_product,
            $id_order,
            $id_storage,
            $order_date,
            $delivery_date,
            $status,
        );
        $handler->handle($command);
    }
}