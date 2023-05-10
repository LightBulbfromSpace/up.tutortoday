<?php

namespace Up\Tutortoday\Services;

use Up\Tutortoday\Model\FormObjects\EdFormatForm;
use Up\Tutortoday\Model\Tables\EducationFormatTable;

class EdFormatsService extends BaseService
{
    public function __construct(EdFormatForm $edFormat)
    {
        parent::__construct(new EducationFormatTable(), $edFormat);
    }
}