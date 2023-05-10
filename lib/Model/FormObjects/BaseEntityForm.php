<?php

namespace Up\Tutortoday\Model\FormObjects;

use Bitrix\Main\Type\ParameterDictionary;

class BaseEntityForm
{
    private ?int $ID;
    private ?string $name;

    public function __construct(?int $ID = null, ?string $name = null)
    {
        $this->ID = $ID;
        $this->name = $name;
    }

    public function getID(): ?int
    {
        return $this->ID;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}