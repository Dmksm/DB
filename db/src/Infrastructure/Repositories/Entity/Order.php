<?php
declare(strict_types=1);

namespace App\Infrastructure\Repositories\Entity;

use App\Infrastructure\Repositories\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $id_client;

    #[ORM\Column]
    private float $sum;

    #[ORM\Column]
    private \DateTimeImmutable $order_date;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $status;

    #[ORM\Column(length: 255)]
    private string $address;

    public function getId(): int
    {
        return $this->id;
    }

    public function getIdClient(): int
    {
        return $this->id_client;
    }

    public function setIdClient(int $id_client): static
    {
        $this->id_client = $id_client;

        return $this;
    }

    public function getSum(): float
    {
        return $this->sum;
    }

    public function setSum(float $sum): static
    {
        $this->sum = $sum;

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

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }
}
