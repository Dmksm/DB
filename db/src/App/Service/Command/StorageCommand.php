<?php
declare(strict_types=1);
namespace App\App\Service\Command;

use Symfony\Component\Validator\Constraints as Assert;

class StorageCommand
{

    #[Assert\NotBlank]
    private int $id;
    #[Assert\NotBlank]
    private string $city;
    #[Assert\NotBlank]
    private string $street;
    #[Assert\NotBlank]
    private string $house;

    public function __construct(
        int        $id,
        string     $city,
        string     $street,
        string     $house
    )
    {
        $this->id = $id;
        $this->city = $city;
        $this->street = $street;
        $this->house = $house;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getHouse(): string
    {
        return $this->house;
    }
}