<?php
declare(strict_types=1);
namespace App\Domain\Entity;

class Client
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private \DateTimeImmutable $birthday;
    private string $email;
    private string $password;
    private ?string $patronymic;
    private ?string $photo;
    private ?string $telephone;

    public function __construct(
        int                $id,
        string             $firstName,
        string             $lastName,
        \DateTimeImmutable $birthday,
        string             $email,
        string             $password,
        ?string            $patronymic = null,
        ?string            $photo = null,
        ?string            $telephone = null,
    )
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthday = $birthday;
        $this->email = $email;
        $this->password = $password;
        $this->patronymic = $patronymic;
        $this->photo = $photo;
        $this->telephone = $telephone;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function getBirthday(): \DateTimeImmutable
    {
        return $this->birthday;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }
}
