<?php
declare(strict_types=1);
namespace App\App\Query\DTO;

class ProductInStorage
{
    private int $id;

    private int $id_product;

    private int $id_storage;

    private int $count;
    public function __construct(
        int                $id,
        int $id_product,
        int $id_storage,
        int $count,
    )
    {
        $this->id = $id;
        $this->id_product = $id_product;
        $this->id_storage = $id_storage;
        $this->count = $count;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getIdProduct(): int
    {
        return $this->id_product;
    }

    public function getIdStorage(): int
    {
        return $this->id_storage;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
