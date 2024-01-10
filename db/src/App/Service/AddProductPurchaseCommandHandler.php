<?php
declare(strict_types=1);
namespace App\App\Service;

use App\App\Service\Command\AddProductPurchaseCommand;
use App\Domain\Service\ProductPurchaseRepositoryInterface;
use App\Domain\Service\ProductPurchaseService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddProductPurchaseCommandHandler
{
    private ProductPurchaseService $productPurchaseService;
    
    /**
     * @param ValidatorInterface $validator
     * @param ProductPurchaseRepositoryInterface $productPurchaseRepository
     */
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly ProductPurchaseRepositoryInterface $productPurchaseRepository
    )
    {
        $this->productPurchaseService = new ProductPurchaseService($this->productPurchaseRepository);
    }

    /**
     * @param  AddProductPurchaseCommand $command
     * @throws BadRequestHttpException
     */
    public function handle(AddProductPurchaseCommand $command): void
    {
        $errors = $this->validator->validate($command);
        if (count($errors) != 0)
        {
            $error = $errors->get(0)->getMessage();
            throw new BadRequestHttpException($error, null, 400);
        }
        
        $this->productPurchaseService->AddProductPurchase(
            $command->getIdProduct(),
            $command->getIdOrder(),
            $command->getIdStorage(),
            $command->getOrderDate(),
            $command->getDeliveryDate(),
            $command->getStatus(),
        );
    }
}