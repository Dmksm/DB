<?php
declare(strict_types=1);
namespace App\App\Service\Command;

use Symfony\Component\Validator\Constraints as Assert;

class StaffInStorageCommand
{

    #[Assert\NotBlank]
    private int $id;
    #[Assert\NotBlank]
    private int $id_staff;
    #[Assert\NotBlank]
    private int $id_storage;

    public function __construct(
        int     $id,
        int     $id_staff,
        int     $id_storage,
    )
    {
        $this->id = $id;
        $this->id_staff = $id_staff;
        $this->id_storage = $id_storage;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getIdStaff(): int
    {
        return $this->id_staff;
    }

    public function getIdStorage(): int
    {
        return $this->id_storage;
    }
}