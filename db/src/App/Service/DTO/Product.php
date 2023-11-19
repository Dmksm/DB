<?php
declare(strict_types=1);

namespace App\App\Service\DTO;

class Product
{
    private int $id;

    private string $name;

    private string $descryption;

    private int $category;

    private float $cost;

    private ?string $photo = null;

    public function __construct(
        string  $name,
        string  $descryption,
        int     $category,
        int     $cost,
        ?string $photo = null,
    )
    {
        $this->name = $name;
        $this->descryption = $descryption;
        $this->category = $category;
        $this->cost = $cost;
        $this->photo = $photo;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescryption(): string
    {
        return $this->descryption;
    }

    public function setDescryption(string $descryption): static
    {
        $this->descryption = $descryption;

        return $this;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function setCategory(int $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getCost(): float
    {
        return $this->cost;
    }

    public function setCost(float $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }
}
