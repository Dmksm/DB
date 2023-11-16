<?php
declare(strict_types=1);
namespace App\App\Service;

use App\App\Service\Command\GetUserInfoCommand;
use App\App\Service\DTO\UserInfo;
use App\Infrastructure\Repository\Service\StaffInfoRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetUserInfoCommandHandler
{
    private ValidatorInterface $validator;
    private StaffInfoRepositoryInterface $staffInfoRepositoryService;

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
    public function handle(GetUserInfoCommand $command): UserInfo
    {
        $errors = $this->validator->validate($command);
        if (count($errors) != 0)
        {
            $error = $errors->get(0)->getMessage();
            throw new BadRequestHttpException($error, null,400);
        }

        $id = $command->getId();
        $user = $this->staffInfoRepositoryService->findOneById($id);
        if ($user)
        {
            throw new \Exception("user with id $id not found");
        }

        return new UserInfo(
            $user->getId(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getBirthday(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getPatronymic(),
            $user->getPhoto(),
            $user->getTelephone(),
            $user->getPosition()
        );
    }
}