<?php
declare(strict_types=1);
namespace App\App\Service\Command;

use Symfony\Component\Validator\Constraints as Assert;

class OrderCommand
{

    #[Assert\NotBlank]
    private int $id;

    #[Assert\NotBlank]
    private int $id_client;

    #[Assert\NotBlank]
    private float $sum;

    #[Assert\NotBlank]
    private \DateTimeImmutable $order_date;

    #[Assert\NotBlank]
    private int $status;

    #[Assert\NotBlank]
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