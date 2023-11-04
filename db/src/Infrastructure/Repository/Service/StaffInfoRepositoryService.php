<?php
declare(strict_types=1);
namespace App\Infrastructure\Repository\Service;

use App\App\Service\StaffInfoRepositoryServiceInterface;
use App\App\Service\DTO\UserInfo;
use App\Infrastructure\Repository\Entity\StaffInfo;

class StaffInfoRepositoryService implements StaffInfoRepositoryServiceInterface
{
    private StaffInfoRepositoryInterface $staffInfoRepository;
    public function __construct(
        StaffInfoRepositoryInterface $staffInfoRepository
    )
    {
        $this->staffInfoRepository = $staffInfoRepository;
    }

    public function getUser(int $id): ?UserInfo
    {
        $user = $this->staffInfoRepository->findOneById($id);
        return ($user) ? $this->toUserInfo($user) : null;
    }

    /**
     * @throws \Exception
     */
    public function saveStaffInfo(UserInfo $user): void
    {
        $this->staffInfoRepository->add($this->toStaffInfo($user));
    }

    private function toStaffInfo(UserInfo $user): StaffInfo
    {
        $entity = new StaffInfo();

        if (!$user->getPosition())
        {
            throw new \Exception("User with id {$user->getId()} aren't a staff member!", 400);
        }
        $entity->setPosition($user->getPosition());
        $entity->setTelephone($user->getTelephone());
        $entity->setPassword($user->getPassword());
        $entity->setFirstName($user->getFirstName());
        $entity->setLastName($user->getLastName());
        $entity->setPatronymic($user->getPatronymic());
        $entity->setEmail($user->getEmail());
        $entity->setBirthday($user->getBirthday());
        $entity->setPhoto($user->getPhoto());

        return $entity;
    }

    private function toUserInfo(StaffInfo $staffInfo): UserInfo
    {
        return new UserInfo(
            $staffInfo->getId(),
            $staffInfo->getFirstName(),
            $staffInfo->getLastName(),
            $staffInfo->getBirthday(),
            $staffInfo->getEmail(),
            $staffInfo->getPassword(),
            $staffInfo->getPatronymic(),
            $staffInfo->getPhoto(),
            $staffInfo->getTelephone(),
            $staffInfo->getPosition()
        );
    }
}
