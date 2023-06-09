<?php

namespace Up\Tutortoday\Providers;

use Bitrix\Main\UserTable;
use Up\Tutortoday\Model\Tables\FeedbacksTable;
use Up\Tutortoday\Model\Tables\ProfileImagesTable;
use Up\Tutortoday\Model\Tables\RolesTable;

class FeedbackProvider
{
    const feedbacksByPage = 3;
    private int $userID;
    public function __construct(int $userID)
    {
        $this->userID = $userID;
    }
    public function getByPage(int $tutorID, int $page, int $tutorsPerPage = self::feedbacksByPage) : ?array
    {
        if ($tutorID < 1 || $page < 0 || $tutorsPerPage < 1)
        {
            return null;
        }

        try
        {
            $roleID = UserTable::query()
                ->setSelect(['WORK_POSITION'])
                ->where('ID', $this->userID)
                ->fetchObject();

            $roleID = $roleID['WORK_POSITION'];
            if ($roleID == null)
            {
                return null;
            }

            $role = RolesTable::query()
                ->setSelect(['NAME'])
                ->where('ID', $roleID)
                ->fetchObject();

            if ($role['NAME'] === 'tutor')
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
        }
        catch (\Exception $e)
        {
            return null;
        }


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

    public function getAllFeedbacksCount(int $tutorID) {
        return FeedbacksTable::query()
            ->setSelect(['*', 'STUDENT'])
            ->where('TUTOR_ID', $tutorID)
            ->fetchCollection()
            ->count();
    }
}