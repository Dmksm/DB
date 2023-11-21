<?php
declare(strict_types=1);

namespace App\Infrastructure\Repositories\Entity;

use App\Infrastructure\Repositories\Repository\ProductInStorageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductInStorageRepository::class)]
class ProductInStorage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $id_product;

    #[ORM\Column]
    private int $id_storage;

    #[ORM\Column]
    private int $count;

    public function getId(): int
    {
        return $this->id;
    }

    public function getIdProduct(): int
    {
        return $this->id_product;
    }

    public function setIdProduct(int $id_product): static
    {
        $this->id_product = $id_product;

        return $this;
    }

    public function getIdStorage(): int
    {
        return $this->id_storage;
    }

    public function setIdStorage(int $id_storage): static
    {
        $this->id_storage = $id_storage;

        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): static
    {
        $this->count = $count;

        return $this;
    }
}
