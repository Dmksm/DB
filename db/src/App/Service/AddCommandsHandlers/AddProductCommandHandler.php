<?php
declare(strict_types=1);
namespace App\App\Service\AddCommandsHandlers;

use App\App\Service\Command\ProductCommand;
use App\Domain\Service\ProductRepositoryInterface;
use App\Domain\Service\ProductService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddProductCommandHandler
{
    private ProductService $productService;
    
    /**
     * @param ValidatorInterface $validator
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly ProductRepositoryInterface $productRepository
    )
    {
        $this->productService = new ProductService($this->productRepository); 
    }

    /**
     * @param  ProductCommand $command
     * @throws BadRequestHttpException
     */
    public function handle(ProductCommand $command): void
    {
        $errors = $this->validator->validate($command);
        if (count($errors) != 0)
        {
            $error = $errors->get(0)->getMessage();
            throw new BadRequestHttpException($error, null, 400);
        }
        
        $this->productService->addProduct( 
            $command->getName(),
            $command->getDescryption(),
            $command->getCategory(),
            $command->getCost(),
            $command->getPhoto(),
        );
    }
}