<?php

namespace Up\Tutortoday\Model\FormObjects;

use Bitrix\Main\Type\ParameterDictionary;
use Up\Tutortoday\Model\Validator;

class UserForm extends BaseEntityForm
{
    private ?string $login;
    private ?string $lastName;
    private ?string $middleName;
    private ?string $oldPassword;
    private ?string $password;
    private ?string $confirmPassword;
    private ?string $email;
    private ?string $workingEmail;
    private ?string $phoneNumber;
    private array $subjectsIDs = [];
    private array $existingSubjectsPrices = [];
    private array $edFormatsIDs = [];
    private ?string $description;
    private ?int $cityID;
    private ?int $roleID;
    private array $newSubjects;

    public function __construct(
        ?int $ID = null,
        ?string $name  = null, ?string $lastName  = null, ?string $middleName  = null,
        ?int $roleID = null,
        ?string $login = null,
        ?string $oldPassword = null, ?string $password = null, ?string $confirmPassword = null,
        ?string $email = null, ?string $workingEmail = null,
        ?string $phoneNumber = null,
        ?string $description = null,
        ?int $cityID = null,
        ?array $edFormatsIDs = null,
        ?array $subjectsIDs = null, ?array $subjectsPrices = null,
        ?array $newSubjectsIDs = null, $newSubjectsPrices = null,
    )
    {
        parent::__construct($ID, $name);

        $this->login = $login ?? '';
        $this->name = $name;
        $this->lastName = trim($lastName);
        $this->middleName = trim($middleName) ?? '';
        $this->oldPassword = trim($oldPassword) ?? '';
        $this->password = trim($password) ?? '';
        $this->confirmPassword = trim($confirmPassword) ?? '';
        $this->email = trim($email) ?? '';
        $this->workingEmail = trim($workingEmail);
        $this->phoneNumber = trim($phoneNumber);
        $this->edFormatsIDs = $edFormatsIDs ?? [];
        $this->description = $description;
        $this->cityID = $cityID;
        $this->roleID = $roleID;
        $this->subjectsIDs = $subjectsIDs ?? [];
        foreach ($subjectsPrices as $ID => $subjectPrice)
        {
            $this->existingSubjectsPrices[] = [
                'ID' => $ID,
                'price' => $subjectPrice,
            ];
        }
        $this->newSubjects = [];
        if ($newSubjectsIDs != null)
        {
            foreach ($newSubjectsIDs as $i => $subjectID)
            {
                $this->newSubjects[] = [
                    'ID' => (int)$subjectID,
                    'price' => $newSubjectsPrices[$i]
                ];
            }
        }
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }
    public function getLastName(): ?string
    {
        return $this->lastName;
    }
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }


    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getWorkingEmail(): ?string
    {
        return $this->workingEmail;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function getEdFormatsIDs(): ?array
    {
        return $this->edFormatsIDs;
    }

    public function getSubjectsIDs(): ?array
    {
        return $this->subjectsIDs;
    }

    public function getExistingSubjectsPrices(): array
    {
        return $this->existingSubjectsPrices;
    }

    public function getDescription(): ?string
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