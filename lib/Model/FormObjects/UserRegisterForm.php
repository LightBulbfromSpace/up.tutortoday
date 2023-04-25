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
    private int $edFormatID;
    private string $description;
    private string $city;
    private int $roleID;

    private array $newSubjects;

    public function __construct(ParameterDictionary $post)
    {
        $this->login = $post['login'] ?? '';
        $this->name = $post['name'];
        $this->lastName = $post['lastName'];
        $this->middleName = $post['middleName'] ?? '';
        $this->password = $post['password'] ?? '';
        $this->confirmPassword = $post['confirmPassword'] ?? '';
        $this->email = $post['email'] ?? '';
        $this->workingEmail = $post['workingEmail'];
        $this->phoneNumber = $post['phoneNumber'];
        $this->edFormatID = $post['edFormat'] ?? 1;
        $this->description = $post['description'];
        $this->city = $post['city'];
        $this->roleID = $post['roleID'] ?? 1;
        $this->subjectsIDs = $post['subjects'] ?? [];
        if ($post['newSubjectsID'] != null)
        {
            foreach ($post['newSubjectsID'] as $i => $subjectID)
            {
                $this->newSubjects[] = [
                    'ID' => (int)$subjectID,
                    'price' => $post['newSubjectsPrices'][$i]
                ];
            }
        }
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

    public function getEdFormatID(): int
    {
        return $this->edFormatID;
    }

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

    public function getRoleID(): int
    {
        return $this->roleID;
    }

    public function getNewSubjects(): array
    {
        return $this->newSubjects;
    }
}