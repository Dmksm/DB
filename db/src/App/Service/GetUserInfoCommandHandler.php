<?php
declare(strict_types=1);
namespace App\App\Service;

use App\Api\User\DTO\UserInfo;
use App\App\Service\Command\GetUserInfoCommand;
use App\Infrastructure\Repository\Service\StaffInfoRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetUserInfoCommandHandler
{
    protected ValidatorInterface $validator;
    protected StaffInfoRepositoryInterface $staffInfoRepositoryService;

    /**
     * Dependency Injection constructor.
     *
     * @param ValidatorInterface $validator
     * @param StaffInfoRepositoryInterface $staffInfoRepositoryService
     */
    public function __construct(
        ValidatorInterface $validator,
        StaffInfoRepositoryInterface $staffInfoRepositoryService
    )
    {
        $this->validator = $validator;
        $this->doctrine  = $staffInfoRepositoryService;
    }

    /**
     * Creates new project.
     *
     * @param  GetUserInfoCommand $command
     * @throws BadRequestHttpException
     */
    public function handle(GetUserInfoCommand $command)
    {
        $violations = $this->validator->validate($command);

        if (count($violations) != 0) {
            $error = $violations->get(0)->getMessage();
            throw new BadRequestHttpException($error);
        }


    }
}