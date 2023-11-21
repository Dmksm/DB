<?php
declare(strict_types=1);

namespace App\Infrastructure\Repositories\Entity;

use App\Infrastructure\Repositories\Repository\ProductPurchaseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductPurchaseRepository::class)]
class ProductPurchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $id_product;

    #[ORM\Column]
    private int $id_order;

    #[ORM\Column]
    private int $id_storage;

    #[ORM\Column]
    private \DateTimeImmutable $order_date;

    #[ORM\Column]
    private \DateTimeImmutable $delivery_date;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $status;

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

    public function getIdOrder(): int
    {
        return $this->id_order;
    }

    public function setIdOrder(int $id_order): static
    {
        $this->id_order = $id_order;

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

    public function getOrderDate(): \DateTimeImmutable 
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTimeImmutable $order_date): static
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getDeliveryDate(): \DateTimeImmutable
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(\DateTimeImmutable $delivery_date): static
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }
}
