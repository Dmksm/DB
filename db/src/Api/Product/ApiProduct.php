<?php
declare(strict_types=1);

namespace App\Api\Product;

use App\App\Query\DTO\Product;
use App\App\Query\ProductQueryServiceInterface;
use App\App\Service\Command\AddProductCommand;
use App\App\Service\AddProductCommandHandler;
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
    
    public function getProduct(int $id): Product
    {
        return $this->productQueryService->getProduct($id);
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
        $command = new AddProductCommand(
            $name,
            $descryption,
            $category,
            $cost,
            $photo,
        );
        $handler->handle($command);

    }
}