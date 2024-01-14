<?php
declare(strict_types=1);

namespace App\Api\Product;

use App\App\Query\DTO\ProductCategory;
use App\App\Query\ProductCategoryQueryServiceInterface;
use App\App\Service\Command\ProductCategoryCommand;
use App\App\Service\AddCommandsHandlers\AddProductCategoryCommandHandler;
use App\App\Service\UpdateCommandsHandlers\UpdateProductCategoryCommandHandler;
use App\Infrastructure\Repositories\Repository\ProductCategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiProductCategory implements ApiProductCategoryInterface
{
    public function __construct(
        private readonly ManagerRegistry                $doctrine,
        private readonly ValidatorInterface             $validator,
        private readonly ProductCategoryQueryServiceInterface $productCategoryQueryService,
    )
    {
    }
    
    public function getProductCategory(int $id): ?ProductCategory
    {
        return $this->productCategoryQueryService->getProductCategory($id);
    }

    public function addProductCategory(string $name): void
    {
        $productCategoryRepository = new ProductCategoryRepository($this->doctrine);
        $handler = new AddProductCategoryCommandHandler($this->validator, $productCategoryRepository);
        $command = new ProductCategoryCommand(
            0,
            $name,
        );
        $handler->handle($command);

    }

    public function updateProductCategory(int $id, string $name): void
    {
        $productCategoryRepository = new ProductCategoryRepository($this->doctrine);
        $handler = new UpdateProductCategoryCommandHandler($this->validator, $productCategoryRepository);
        $command = new ProductCategoryCommand(
            $id,
            $name,
        );
        $handler->handle($command);

    }
}