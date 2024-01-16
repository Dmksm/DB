<?php
declare(strict_types=1);

namespace App\Domain\Entity;

class Product
{
    private int $id;

    private string $name;

    private string $description;

    private int $category;

    private float $cost;

    private ?string $photo = null;

    public function __construct(
        int     $id,
        string  $name,
        string  $description,
        int     $category,
        int     $cost,
        ?string $photo = null,
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
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

    public function getdescription(): string
    {
        return $this->description;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function getCost(): float
    {
        return $this->cost;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }
}
