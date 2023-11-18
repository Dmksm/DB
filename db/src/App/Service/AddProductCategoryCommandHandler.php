<?php
declare(strict_types=1);
namespace App\App\Service;

use App\App\Service\Command\AddProductCategoryCommand;
use App\Domain\Service\ProductCategoryRepositoryInterface;
use App\Domain\Service\ProductCategoryService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddProductCategoryCommandHandler
{
    private ProductCategoryService $productCategoryService;
    
    /**
     * @param ValidatorInterface $validator
     * @param ProductCategoryRepositoryInterface $productCategoryRepository
     */
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly ProductCategoryRepositoryInterface $productCategoryRepository
    )
    {
        $this->productCategoryService = new ProductCategoryService($this->productCategoryRepository);
    }

    /**
     * @param  AddProductCategoryCommand $command
     * @throws BadRequestHttpException
     */
    public function handle(AddProductCategoryCommand $command): void
    {
        $errors = $this->validator->validate($command);
        if (count($errors) != 0)
        {
            $error = $errors->get(0)->getMessage();
            throw new BadRequestHttpException($error, null,400);
        }
        
        $this->productCategoryService->AddProductCategory(
            $command->getName()
        );
    }
}