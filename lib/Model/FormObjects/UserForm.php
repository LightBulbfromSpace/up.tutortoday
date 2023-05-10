<?php

namespace Up\Tutortoday\Model\FormObjects;

use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Model\Validator;

class UserForm
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

    private array $existingSubjectsPrices = [];

    private array $edFormatsIDs = [];
    private string $description;
    private ?int $cityID;
    private ?int $roleID;
    private array $newSubjects;

    public function __construct(ParameterDictionary $post)
    {
        $this->login = $post['login'] ?? '';
        $this->name = trim($post['name']);
        $this->lastName = trim($post['lastName']);
        $this->middleName = trim($post['middleName']) ?? '';
        $this->password = trim($post['password']) ?? '';
        $this->confirmPassword = trim($post['confirmPassword']) ?? '';
        $this->email = trim($post['email']) ?? '';
        $this->workingEmail = trim($post['workingEmail']);
        $this->phoneNumber = trim($post['phoneNumber']);
        $this->edFormatsIDs = $post['edFormats'] ?? [];
        $this->description = $post['description'];
        $this->cityID = !is_numeric($post['city']) ? null : (int)$post['city'];
        $this->roleID = !is_numeric($post['role']) ? null : (int)$post['role'];
        $this->subjectsIDs = $post['subjects'] ?? [];
        foreach ($post['subjectsPrices'] as $ID => $subjectPrice)
        {
            $this->existingSubjectsPrices[] = [
                'ID' => $ID,
                'price' => $subjectPrice,
            ];
        }
        $this->newSubjects = [];
        if (isset($post['newSubjectsID']) && $post['newSubjectsID'] != null)
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

    public function getEdFormatsIDs(): array
    {
        return $this->edFormatsIDs;
    }

    public function getSubjectsIDs(): array
    {
        return $this->subjectsIDs;
    }

    public function getExistingSubjectsPrices(): array
    {
        return $this->existingSubjectsPrices;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCityID(): ?int
    {
        return $this->cityID;
    }

    public function getRoleID(): ?int
    {
        return $this->roleID;
    }

    public function getNewSubjects(): array
    {
        return $this->newSubjects;
    }
}