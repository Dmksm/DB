<?php
declare(strict_types=1);

namespace App\Infrastructure\Repositories\Entity;

use App\Infrastructure\Repositories\Repository\StaffInStorageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StaffInStorageRepository::class)]
class StaffInStorage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $id_staff;

    #[ORM\Column]
    private int $id_storage;

    public function getId(): int
    {
        return $this->id;
    }

    public function getIdStaff(): int
    {
        return $this->id_staff;
    }

    public function setIdStaff(int $id_staff): static
    {
        $this->id_staff = $id_staff;

        return $this;
    }

    public function getIdStorage(): int
    {
        return $this->id_storage;
    }

    public function setIdStorage(int $id_storage): static
    {
        $this->id_storage = $id_storage;

        return $this;
    }
}
