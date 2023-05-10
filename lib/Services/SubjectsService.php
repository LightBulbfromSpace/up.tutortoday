<?php

namespace Up\Tutortoday\Services;

use Up\Tutortoday\Model\FormObjects\SubjectForm;
use Up\Tutortoday\Model\Tables\SubjectTable;
use Up\Tutortoday\Model\Tables\UserSubjectTable;

class SubjectsService extends BaseService
{
    public function __construct($subject)
    {
        parent::__construct(new SubjectTable(), $subject);
    }
}