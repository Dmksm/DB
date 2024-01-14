<?php
declare(strict_types=1);

namespace App\Api\Product;

use App\App\Query\DTO\Product;
use App\App\Query\ProductQueryServiceInterface;
use App\App\Service\Command\ProductCommand;
use App\App\Service\AddCommandsHandlers\AddProductCommandHandler;
use App\App\Service\UpdateCommandsHandlers\UpdateProductCommandHandler;
use App\Infrastructure\Repositories\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiProduct implements ApiProductInterface
{
    public function __construct(
        private readonly ManagerRegistry                $doctrine,
        private readonly ValidatorInterface             $validator,
        private readonly ProductQueryServiceInterface $productQueryService, 
    )
    {
    }
    
    public function getProduct(int $id): ?Product
    {
        return $this->productQueryService->getProduct($id);
    }

    
    public function getProductsByCategory(int $categoryId): array
    {
        return $this->productQueryService->getProductsByCategory($categoryId);
    }
    
    public function getProductsByIncludingString(string $subString): array
    {
        return $this->productQueryService->getProductsByIncludingString($subString);

    }

    public function addProduct(
        string      $name,
        string      $descryption,
        int         $category,
        int         $cost,
        string|null $photo = null
        ): void
    {
        $productRepository = new ProductRepository($this->doctrine);  
        $handler = new AddProductCommandHandler($this->validator, $productRepository); 
        $command = new ProductCommand(
            0,
            $name,
            $descryption,
            $category,
            $cost,
            $photo,
        );
        $handler->handle($command);

    }

    public function updateProduct(
        int         $id,
        string      $name,
        string      $descryption,
        int         $category,
        int         $cost,
        string|null $photo = null
        ): void
    {
        $productRepository = new ProductRepository($this->doctrine);  
        $handler = new UpdateProductCommandHandler($this->validator, $productRepository); 
        $command = new ProductCommand(
            $id,
            $name,
            $descryption,
            $category,
            $cost,
            $photo,
        );
        $handler->handle($command);

    }

    public function getAllProducts(): array
    {
        return $this->productQueryService->getAllProducts();
    }
}