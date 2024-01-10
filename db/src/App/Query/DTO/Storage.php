<?php
declare(strict_types=1);
namespace App\App\Query\DTO;

class Storage
{
    private int $id;

    private string $city;

    private string $street;

    private string $house;
    public function __construct(
        int                $id,
        string             $city,
        string             $street,
        string             $house
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
