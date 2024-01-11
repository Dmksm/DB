<?php
declare(strict_types=1);
namespace App\App\Service\AddCommandsHandlers;

use App\App\Service\Command\ProductInStorageCommand;
use App\Domain\Service\ProductInStorageRepositoryInterface;
use App\Domain\Service\ProductInStorageService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddProductInStorageCommandHandler
{
    private ProductInStorageService $productInstorageService;
    
    /**
     * @param ValidatorInterface $validator
     * @param ProductInStorageRepositoryInterface $productInstorageRepository
     */
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly ProductInStorageRepositoryInterface $productInstorageRepository
    )
    {
        $this->productInstorageService = new ProductInStorageService($this->productInstorageRepository); 
    }

    /**
     * @param  ProductInStorageCommand $command
     * @throws BadRequestHttpException
     */
    public function handle(ProductInStorageCommand $command): void
    {
        $errors = $this->validator->validate($command);
        if (count($errors) != 0)
        {
            $error = $errors->get(0)->getMessage();
            throw new BadRequestHttpException($error, null, 400);
        }
        
        $this->productInstorageService->addProductInStorage( 
            $command->getIdProduct(),
            $command->getIdStorage(),
            $command->getCount(),
        );
    }
}