<?php
declare(strict_types=1);
namespace App\App\Service\Command;

use Symfony\Component\Validator\Constraints as Assert;

class ProductCategoryCommand
{

    #[Assert\NotBlank]
    private int $id;
    #[Assert\NotBlank]
    private string $name;

    public function __construct(
        int    $id,
        string $name,
    )
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}