<?php
declare(strict_types=1);
namespace App\App\Service\Command;

use Symfony\Component\Validator\Constraints as Assert;

class AddProductCategoryCommand
{
    #[Assert\NotBlank]
    private string $name;

    public function __construct(
        string             $name,
    )
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}