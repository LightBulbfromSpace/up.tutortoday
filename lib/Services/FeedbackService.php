<?php

namespace Up\Tutortoday\Services;

use Up\Tutortoday\Model\FormObjects\FeedbackForm;
use Up\Tutortoday\Model\Tables\FeedbacksTable;
use Up\Tutortoday\Model\Tables\ProfileImagesTable;
use Up\Tutortoday\Model\Tables\UserRoleTable;

class FeedbackService
{

    const feedbacksByPage = 3;
    private int $userID;
    public function __construct(int $userID)
    {
        $this->userID = $userID;
    }

    public function add(FeedbackForm $feedbackForm)
    {
        FeedbacksTable::add([
            'TUTOR_ID' => $feedbackForm->getReceiverID(),
            'STUDENT_ID' => $this->userID,
            'TITLE' => $feedbackForm->getTitle(),
            'DESCRIPTION' => $feedbackForm->getDescription(),
            'STARS_NUMBER' => $feedbackForm->getStars(),
        ]);
        return $feedbackForm->getStars();
    }

    public function getAllFeedbacksCount(int $tutorID) {
        return FeedbacksTable::query()
            ->setSelect(['*', 'STUDENT'])
            ->where('TUTOR_ID', $tutorID)
            ->fetchCollection()
            ->count();
    }

    public function getByPage(int $tutorID, int $page, int $tutorsPerPage = self::feedbacksByPage)
    {
        $role = UserRoleTable::query()
            ->setSelect(['*', 'ROLE'])
            ->where('USER_ID', $this->userID)
            ->fetchObject();

        if ($role['ROLE']['NAME'] === 'tutor')
        {
            return null;
        }

        $feedbacks = FeedbacksTable::query()
            ->setSelect(['*', 'STUDENT'])
            ->where('TUTOR_ID', $tutorID)
            ->setOrder(['ID' => 'DESC'])
            ->setOffset($page * $tutorsPerPage)
            ->setLimit($tutorsPerPage)
            ->fetchCollection();
        $studentIDs = [];

        if ($feedbacks->count() === 0) {
            return null;
        }

        foreach ($feedbacks as $feedback)
        {
            $studentIDs[] = $feedback['STUDENT']['ID'];
        }

        $studentPhotos = ProfileImagesTable::query()
            ->setSelect(['USER_ID', 'LINK'])
            ->whereIn('USER_ID', $studentIDs)
            ->fetchCollection();



        $feedbacksArr = [];
        foreach ($feedbacks as $i => $feedback)
        {
            $feedbacksArr[$i] = [
                'tutorID' => $feedback['TUTOR_ID'],
                'studentID' => $feedback['STUDENT_ID'],
                'title' => $feedback['TITLE'],
                'description' => $feedback['DESCRIPTION'],
                'stars' => $feedback['STARS_NUMBER'],
                'student' => [
                    'ID' => $feedback['STUDENT']['ID'],
                    'name' => $feedback['STUDENT']['NAME'],
                    'surname' => $feedback['STUDENT']['LAST_NAME'],
                ]
            ];
            foreach ($studentPhotos as $studentPhoto)
            {
                if ($studentPhoto['USER_ID'] === $feedback['STUDENT_ID'])
                {
                    $feedbacksArr[$i]['student']['photo'] = $studentPhoto['LINK'];
                    break;
                }
                $feedbacksArr[$i]['student']['photo'] = DEFAULT_PHOTO;
            }
        }

        return array_values($feedbacksArr);
    }
}