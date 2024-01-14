<?php
declare(strict_types=1);
namespace App\App\Service\UpdateCommandsHandlers;

use App\App\Service\Command\ProductCategoryCommand;
use App\Domain\Service\ProductCategoryRepositoryInterface;
use App\Domain\Service\ProductCategoryService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateProductCategoryCommandHandler
{
    private ProductCategoryService $productCategoryService;
    
    /**
     * @param ValidatorInterface $validator
     * @param ProductCategoryRepositoryInterface $ProductCategoryRepository
     */
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly ProductCategoryRepositoryInterface $ProductCategoryRepository
    )
    {
        $this->productCategoryService = new ProductCategoryService($this->ProductCategoryRepository);
    }

    /**
     * @param ProductCategoryCommand $command
     * @throws BadRequestHttpException
     */
    public function handle(ProductCategoryCommand $command): void
    {
        $errors = $this->validator->validate($command);
        if (count($errors) != 0)
        {
            $error = $errors->get(0)->getMessage();
            throw new BadRequestHttpException($error, null, 400);
        }
        
        $this->productCategoryService->update(
            $command->getId(),
            $command->getName(),
        );
    }
}