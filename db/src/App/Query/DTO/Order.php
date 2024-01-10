<?php
declare(strict_types=1);
namespace App\App\Query\DTO;

class Order
{
    private int $id;

    private int $id_client;

    private float $sum;

    private \DateTimeImmutable $order_date;

    private int $status;

    private string $address;

    public function __construct(
        int                $id,
        int                $id_client,
        float              $sum,
        \DateTimeImmutable $order_date,
        int                $status,
        string             $address,
    )
    {
        $this->id = $id;
        $this->id_client = $id_client;
        $this->sum = $sum;
        $this->order_date = $order_date;
        $this->status = $status;
        $this->address = $address;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getIdClient(): int
    {
        return $this->id_client;
    }

    public function getSum(): float
    {
        return $this->sum;
    }

    public function getOrderDate(): \DateTimeImmutable
    {
        return $this->order_date;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}