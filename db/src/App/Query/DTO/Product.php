<?php
declare(strict_types=1);

namespace App\App\Query\DTO;

class Product
{
    private int $id;

    private string $name;

    private string $description;

    private int $category_id;

    private float $cost;

    private ?string $photo = null;

    public function __construct(
        string  $name,
        string  $description,
        int     $category_id,
        int     $cost,
        ?string $photo = null,
    )
    {
        $this->name = $name;
        $this->description = $description;
        $this->category_id = $category_id;
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
        return $this->category_id;
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
