<?php
declare(strict_types=1);
namespace App\App\Service\Command;

use Symfony\Component\Validator\Constraints as Assert;

class ProductPurchaseCommand
{

    #[Assert\NotBlank]
    private int $id;
    #[Assert\NotBlank]
    private int $id_product;

    #[Assert\NotBlank]
    private int $id_order;

    #[Assert\NotBlank]
    private int $id_storage;

    #[Assert\NotBlank]
    private \DateTimeImmutable $order_date;

    #[Assert\NotBlank]
    private ?\DateTimeImmutable $delivery_date;

    #[Assert\NotBlank]
    private int $status;

    public function __construct(
        int                $id,
        int                $id_product,
        int                $id_order,
        int                $id_storage,
        \DateTimeImmutable $order_date,
        ?\DateTimeImmutable $delivery_date,
        int                $status,
    )
    {
        $this->id = $id;
        $this->id_product = $id_product;
        $this->id_order = $id_order;
        $this->id_storage = $id_storage;
        $this->order_date = $order_date;
        $this->delivery_date = $delivery_date;
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getIdProduct(): int
    {
        return $this->id_product;
    }

    public function getIdOrder(): int
    {
        return $this->id_order;
    }

    public function getIdStorage(): int
    {
        return $this->id_storage;
    }

    public function getOrderDate(): \DateTimeImmutable 
    {
        return $this->order_date;
    }

    public function getDeliveryDate(): ?\DateTimeImmutable
    {
        return $this->delivery_date;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}