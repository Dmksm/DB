<?php
declare(strict_types=1);

namespace App\Api\Product;

use App\App\Query\DTO\ProductCategory;
use App\App\Query\ProductCategoryQueryServiceInterface;
use App\App\Service\Command\AddProductCategoryCommand;
use App\App\Service\AddProductCategoryCommandHandler;
use App\Infrastructure\Repositories\Repository\ProductCategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiProduct implements ApiProductInterface
{
    public function __construct(
        private readonly ManagerRegistry                $doctrine,
        private readonly ValidatorInterface             $validator,
        private readonly ProductCategoryQueryServiceInterface $productCategoryQueryService,
    )
    {
    }
    
    public function getProductCategory(int $id): ProductCategory
    {
        return $this->productCategoryQueryService->getProductCategory($id);
    }

    public function addProductCategory(string $name): void
    {
        $productCategoryRepository = new ProductCategoryRepository($this->doctrine);
        $handler = new AddProductCategoryCommandHandler($this->validator, $productCategoryRepository);
        $command = new AddProductCategoryCommand(
            $name,
        );
        $handler->handle($command);

    }
}