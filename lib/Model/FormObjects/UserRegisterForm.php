<?php

namespace Up\Tutortoday\Model\FormObjects;

use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Model\Validator;

class UserRegisterForm
{
    private string $login;

    private string $name;
    private string $lastName;
    private string $middleName;
    private string $password;
    private string $confirmPassword;
    private string $email;
    private string $workingEmail;


    private string $phoneNumber;
    private array $subjectsIDs;
    private int $edFormat;
    private string $description;
    private string $city;
    private int $roleID;
    public function __construct(ParameterDictionary $post)
    {
        $this->login = $post['login'];
        $this->name = $post['name'];
        $this->lastName = $post['lastName'];
        $this->middleName = $post['middleName'] ?? '';
        $this->password = $post['password'];
        $this->confirmPassword = $post['confirmPassword'];
        $this->email = $post['email'];
        $this->workingEmail = $post['workingEmail'];
        $this->phoneNumber = $post['phoneNumber'];
        $this->edFormat = $post['edFormat'] ?? 1;
        $this->description = $post['description'];
        $this->city = $post['city'];
        $this->roleID = $post['roleID'] ?? 1;
        $this->subjectsIDs = $post['subjects'] ?? [];
    }
    public function getLogin(): string
    {
        return $this->login;
    }
    public function getName(): string
    {
        return $this->name;
    }


    public function getLastName(): string
    {
        return $this->lastName;
    }


    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getWorkingEmail(): string
    {
        return $this->workingEmail;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getEdFormat(): int
    {
        return $this->edFormat;
    }

    /**
     * @return array
     */
    public function getSubjectsIDs(): array
    {
        return $this->subjectsIDs;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return int
     */
    public function getRoleID(): int
    {
        return $this->roleID;
    }
}