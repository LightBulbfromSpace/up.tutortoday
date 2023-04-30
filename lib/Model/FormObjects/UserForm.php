<?php

namespace Up\Tutortoday\Model\FormObjects;

class UserForm
{
    private string $name;
    private string $surname;
    private string $middleName;
    private int $edFormat;
    private string $description;
    private string $city;
    private int $roleID;
    public function __construct(
        string $name, string $surname,  int $edFormat,
        string $middleName = '', string $description = '', string $city = '',
        int $roleID = 1
    )
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->middleName = $middleName;
        $this->edFormat = $edFormat;
        $this->description = $description;
        $this->city = $city;
        $this->roleID = $roleID;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    public function getEdFormat(): int
    {
        return $this->edFormat;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getRoleID(): int
    {
        return $this->roleID;
    }
}