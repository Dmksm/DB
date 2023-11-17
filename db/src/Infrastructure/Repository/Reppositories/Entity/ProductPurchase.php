<?php

namespace App\Entity;

use App\Repository\ProductPurchaseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductPurchaseRepository::class)]
class ProductPurchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_product = null;

    #[ORM\Column]
    private ?int $id_order = null;

    #[ORM\Column]
    private ?int $id_client = null;

    #[ORM\Column]
    private ?int $id_storage = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $order_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $delivery_date = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProduct(): ?int
    {
        return $this->id_product;
    }

    public function setIdProduct(int $id_product): static
    {
        $this->id_product = $id_product;

        return $this;
    }

    public function getIdOrder(): ?int
    {
        return $this->id_order;
    }

    public function setIdOrder(int $id_order): static
    {
        $this->id_order = $id_order;

        return $this;
    }

    public function getIdClient(): ?int
    {
        return $this->id_client;
    }

    public function setIdClient(int $id_client): static
    {
        $this->id_client = $id_client;

        return $this;
    }

    public function getIdStorage(): ?int
    {
        return $this->id_storage;
    }

    public function setIdStorage(int $id_storage): static
    {
        $this->id_storage = $id_storage;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTimeInterface $order_date): static
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(\DateTimeInterface $delivery_date): static
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }
}
