<?php

namespace Up\Tutortoday\Services;

use Up\Tutortoday\Model\FormObjects\FeedbackForm;
use Up\Tutortoday\Model\Tables\FeedbacksTable;

class FeedbackService extends BaseService
{
    private int $userID;
    public function __construct(int $userID, FeedbackForm $feedback)
    {
        $this->userID = $userID;
        parent::__construct(new FeedbacksTable(), $feedback);
    }

    public function addNewEntity() : ?int
    {
        try
        {
            $result = FeedbacksTable::add([
                'TUTOR_ID' => $this->entity->getReceiverID(),
                'STUDENT_ID' => $this->userID,
                'TITLE' => $this->entity->getTitle(),
                'DESCRIPTION' => $this->entity->getDescription(),
                'STARS_NUMBER' => $this->entity->getStars(),
            ]);
        }
        catch (\Exception $e)
        {
            return null;
        }
        return $result->getId();
    }
}