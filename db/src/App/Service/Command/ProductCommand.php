<?php
declare(strict_types=1);
namespace App\App\Service\Command;

use Symfony\Component\Validator\Constraints as Assert;

class ProductCommand
{

    #[Assert\NotBlank]
    private int $id;
    #[Assert\NotBlank]
    private string $name;
    #[Assert\NotBlank]
    private string $description;
    #[Assert\NotBlank]
    private int $category;
    #[Assert\NotBlank]
    private int $cost;
    #[Assert\NotBlank]
    private ?string $photo;

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

    public function getName(): ?string
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

    public function getCost(): int
    {
        return $this->cost;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }
}