<?php

namespace App\Entity\User;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[Entity(repositoryClass:"App\Repository\UsersRepository\UserRepository")]
class User
{
    #[Id]
    #[Column(type: "uuid", unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: UuidGenerator::class)]
    private int $user_uuid;

    #[Column(type: "string")]
    private string $username;

    #[Column(type: "string", length: 150)]
    private string $password;

    #[Column(type: "string")]
    private string $first_name;

    #[Column(type: "string")]
    private string $last_name;

    #[Column(type: "string")]
    private string $user_photo_id;

    #[Column(name: 'date_of_birth', type: Types::DATETIME_MUTABLE)]
    private DateTime $date_of_birth;

    #[Column(name: 'date_of_create', type: Types::DATETIME_MUTABLE)]
    private DateTime $date_of_create;

    #[Column(type: "string", length: 6)]
    private string $gender;

    #[Column(name: 'interests')]
    private array $interestes;

    /**
     * Get the value of user_uuid
     */ 
    public function getUser_uuid(): int
    {
        return $this->user_uuid;
    }

    /**
     * Set the value of user_uuid
     */ 
    public function setUser_uuid($user_uuid): void
    {
        $this->user_uuid = $user_uuid;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     */ 
    public function setUsername($username): void
    {
        $this->username = $username;

    }

    /**
     * Get the value of password
     */ 
    public function getPassword(): int
    {
        return $this->password;
    }

    /**
     * Set the value of password
     * Добавить логику хэширования !!!
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

    }

    /**
     * Get the value of first_name
     */ 
    public function getFirst_name(): string
    {
        return $this->first_name;
    }

    /**
     * Set the value of first_name
     */ 
    public function setFirst_name($first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * Get the value of last_name
     */ 
    public function getLast_name(): string
    {
        return $this->last_name;
    }

    /**
     * Set the value of last_name
     */ 
    public function setLast_name($last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * Get the value of user_photo_id
     */ 
    public function getUser_photo_id(): string
    {
        return $this->user_photo_id;
    }

    /**
     * Set the value of user_photo_id
     */ 
    public function setUser_photo_id($user_photo_id): void
    {
        $this->user_photo_id = $user_photo_id;
    }

    /**
     * Get the value of date_of_birth
     */ 
    public function getDate_of_birth(): DateTime
    {
        return $this->date_of_birth;
    }

    /**
     * Set the value of date_of_birth
     */ 
    public function setDate_of_birth($date_of_birth): void
    {
        $this->date_of_birth = $date_of_birth;

    }

    /**
     * Get the value of date_of_create
     */ 
    public function getDate_of_create(): DateTime
    {
        return $this->date_of_create;
    }

    /**
     * Set the value of date_of_create
     */ 
    public function setDate_of_create($date_of_create): void
    {
        $this->date_of_create = $date_of_create;

    }

    /**
     * Get the value of gender
     */ 
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * Set the value of gender
     */ 
    public function setGender($gender): void
    {
        $this->gender = $gender;

    }

    /**
     * Get the value of interestes
     */ 
    public function getInterestes(): array
    {
        return $this->interestes;
    }

    /**
     * Set the value of interestes
     */ 
    public function setInterestes($interestes): void
    {
        $this->interestes = $interestes;

    }
}