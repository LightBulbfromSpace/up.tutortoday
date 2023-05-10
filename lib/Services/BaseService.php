<?php

namespace Up\Tutortoday\Services;

use Bitrix\Main\ORM\Data\DataManager;
use Up\Tutortoday\Model\FormObjects\BaseEntityForm;

class BaseService
{
    protected BaseEntityForm $entity;
    private DataManager $tableORM;


    public function __construct($tableORM, BaseEntityForm $entity)
    {
        $this->tableORM = $tableORM;
        $this->entity = $entity;
    }

    public function addNewEntity() : ?int
    {
        try
        {
            $result = $this->tableORM::add([
                'NAME' => $this->entity->getName(),
            ]);
        }
        catch (\Exception $e)
        {
            return null;
        }

        return $result->getId();
    }

    public function editEntity() : ?int
    {
        try
        {
            $result = $this->tableORM::update($this->entity->getID(), [
                'NAME' => $this->entity->getName(),
            ]);
        }
        catch (\Exception $e)
        {
            return null;
        }
        return $result->getId();
    }

    public function deleteEntity() : bool
    {
        try
        {
            $result = $this->tableORM::delete($this->entity->getID());
        }
        catch (\Exception $e)
        {
            return false;
        }
        return $result->isSuccess();
    }
}