<?php
declare(strict_types=1);

namespace App\Infrastructure\Repositories\Entity;

use App\Infrastructure\Repositories\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 50)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $descryption;

    #[ORM\Column]
    private int $category_id;

    #[ORM\Column]
    private float $cost;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

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
        return $this->category_id;
    }

    public function setCategory(int $category): static
    {
        $this->category_id = $category;

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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }
}
