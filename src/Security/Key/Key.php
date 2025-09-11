<?php

namespace App\Security\Key;

class Key
{
    public ?string $id = null;
    public ?string $salt = null;
    public ?string $hash = null;

    public const string SEPARATOR = '-';

    public static function create(): self
    {
        return new self();
    }


    public function getId(): string
    {
        return $this->id;
    }


    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }


    public function setSalt(string|null $salt = null): self
    {
        $this->salt = $salt ?? substr(md5(microtime()), 0, 5);

        return $this;
    }

    public function isPopulated(): bool
    {
        return $this->id !== null;
    }
}