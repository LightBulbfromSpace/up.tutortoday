<?php

namespace Up\Tutortoday\Services;

use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UrlPreview\Parser\Vk;
use Bitrix\Main\UserTable;
use CUser;
use Up\Tutortoday\Model\FormObjects\UserForm;
use Up\Tutortoday\Model\Tables\CitiesTable;
use Up\Tutortoday\Model\Tables\FeedbacksTable;
use Up\Tutortoday\Model\Tables\FreeTimeTable;
use Up\Tutortoday\Model\Tables\ProfileImagesTable;
use Up\Tutortoday\Model\Tables\RolesTable;
use Up\Tutortoday\Model\Tables\SubjectTable;
use Up\Tutortoday\Model\Tables\TelegramTable;
use Up\Tutortoday\Model\Tables\UserDescriptionTable;
use Up\Tutortoday\Model\Tables\UserEdFormatTable;
use Up\Tutortoday\Model\Tables\UserSubjectTable;
use Up\Tutortoday\Model\Tables\VkTable;
use Up\Tutortoday\Model\Validator;
use Up\Tutortoday\Providers\FeedbackProvider;
use Up\Tutortoday\Providers\LocationProvider;

class UserService extends BaseService
{
    public function __construct(UserForm $user)
    {
        parent::__construct(new UserTable(), $user);
    }

    public function addNewEntity() : ?int
    {
        try
        {
            global $DB;
            $DB->StartTransaction();
            $user = new \CUser();
            $resultUser = $user->Register(
                $this->entity->getLogin(),
                $this->entity->getName(),
                $this->entity->getLastName(),
                $this->entity->getPassword(),
                $this->entity->getConfirmPassword(),
                $this->entity->getEmail(),
            );

            if ($resultUser['TYPE'] !== 'OK')
            {
                $DB->Rollback();
                return null;
            }

            $resultUser = $user->Update($user->getID(), [
                'SECOND_NAME' => $this->entity->getMiddleName(),
                'WORK_PHONE' => $this->entity->getPhoneNumber(),
                'WORK_MAILBOX' => $this->entity->getWorkingEmail(),
                'WORK_CITY' => $this->entity->getCityID(),
                'WORK_POSITION' => $this->entity->getRoleID(),
                'WORK_COMPANY' => 'TutorToday',
            ]);

            if (!$resultUser)
            {
                $DB->Rollback();
                return null;
            }

            foreach ($this->entity->getEdFormatsIDs() as $edFormatID) {
                $resultEdFormat = UserEdFormatTable::add([
                    'USER_ID' => $user->getID(),
                    'EDUCATION_FORMAT_ID' => $edFormatID,
                ]);
                if (!$resultEdFormat->isSuccess()) {
                    return null;
                }
            }

            foreach ($this->entity->getSubjectsIDs() as $subject)
            {
                $resultSubject = UserSubjectTable::add([
                    'USER_ID' => $user->getID(),
                    'SUBJECT_ID' => $subject,
                    'PRICE' => 0,
                ]);
                if (!$resultSubject->isSuccess())
                {
                    $DB->Rollback();
                    return null;
                }
            }

            $resultDescription = UserDescriptionTable::add([
                'USER_ID' => $user->getID(),
                'DESCRIPTION' => $this->entity->getDescription(),
            ]);
            if (!$resultDescription->isSuccess())
            {
                $DB->Rollback();
                return null;
            }

            $DB->Commit();
        }
        catch (\Exception $e)
        {
            return null;
        }

        return $user->getID();
    }

    public function editEntity() : ?int
    {
        try
        {
            global $DB;
            $DB->StartTransaction();
            $user = new \CUser();
            $userResult = $user->update($this->entity->getID(), [
                'NAME' => $this->entity->getName(),
                'LAST_NAME' => $this->entity->getLastName(),
                'SECOND_NAME' => $this->entity->getMiddleName(),
                'WORK_CITY' => $this->entity->getCityID() ?? '',
                'WORK_PHONE' => $this->entity->getPhoneNumber(),
                'WORK_MAILBOX' => $this->entity->getWorkingEmail(),
            ]);
            if ($userResult !== true)
            {
                $DB->Rollback();
                return $userResult;
            }

            $descriptionResult = UserDescriptionTable::update($this->entity->getID(), [
                'DESCRIPTION' => $this->entity->getDescription()
            ]);
            if (!$descriptionResult->isSuccess())
            {
                $DB->Rollback();
                return 'description update error';
            }

            $existingEdFormats = UserEdFormatTable::query()
                ->setSelect(['*'])
                ->where('USER_ID', $this->entity->getID())
                ->fetchCollection();

            foreach ($existingEdFormats as $format)
            {
                $edFormatResult = UserEdFormatTable::delete([
                    'USER_ID' => $format['USER_ID'],
                    'EDUCATION_FORMAT_ID' => $format['EDUCATION_FORMAT_ID'],
                ]);
                if (!$edFormatResult->isSuccess())
                {
                    $DB->Rollback();
                    return 'education format update error';
                }
            }

            foreach ($this->entity->getEdFormatsIDs() as $edFormatID)
            {
                $edFormatResult = UserEdFormatTable::add([
                    'USER_ID' => $this->entity->getID(),
                    'EDUCATION_FORMAT_ID' => $edFormatID
                ]);
                if (!$edFormatResult->isSuccess())
                {
                    $DB->Rollback();
                    return 'education format error';
                }
            }

            foreach ($this->entity->getExistingSubjectsPrices() as $subject)
            {
                if ($subject['price'] < 0) {
                    continue;
                }
                $subjAddResult = UserSubjectTable::update([
                    'USER_ID' => $this->entity->getID(),
                    'SUBJECT_ID' =>$subject['ID'],
                ], [
                    'PRICE' => $subject['price'],
                ]);
                if (!$subjAddResult->isSuccess())
                {
                    $DB->Rollback();
                    return 'subject\'s price update error';
                }
            }

            $subjectsToAdd = [];

            $existingSubjectsIDs = UserSubjectTable::query()
                ->setSelect(['SUBJECT_ID'])
                ->where('USER_ID', $this->entity->getID())
                ->fetchCollection();

            if ($existingSubjectsIDs != null)
            {
                foreach ($this->entity->getNewSubjects() as $newSubj)
                {
                    $inArray = false;
                    foreach ($existingSubjectsIDs as $exSubjID)
                    {
                        var_dump('ex subj:', $exSubjID['SUBJECT_ID']);
                        if ((int)$newSubj['ID'] === (int)$exSubjID['SUBJECT_ID'])
                        {
                            $inArray = true;
                            break;
                        }
                    }
                    if (!$inArray)
                    {
                        $subjectsToAdd[] = $newSubj;
                    }
                }
            }

            $addedSubjectsIDs = [];
            foreach ($subjectsToAdd as $subj)
            {
                if (in_array($subj['ID'], $addedSubjectsIDs))
                {
                    continue;
                }
                $subjAddResult = UserSubjectTable::add([
                    'USER_ID' => $this->entity->getID(),
                    'SUBJECT_ID' => $subj['ID'],
                    'PRICE' => $subj['price'],
                ]);
                $addedSubjectsIDs[] = $subj['ID'];
                if (!$subjAddResult->isSuccess())
                {
                    $DB->Rollback();
                    return null;
                }
            }
        }
        catch (\Exception $e)
        {
            return null;
        }

        $DB->Commit();
        return $this->entity->getID();
    }

    public function deleteEntity() : bool
    {
        try
        {
            \CUser::Delete($this->entity->getID());
            $subjects = UserSubjectTable::query()->setSelect(['*'])->where('USER_ID', $this->entity->getID())->fetchCollection();
            foreach ($subjects as $subject)
            {
                $result = UserSubjectTable::delete([
                    'USER_ID' => $subject['USER_ID'],
                    'SUBJECT_ID' => $subject['SUBJECT_ID'],
                ]);
                if (!$result->isSuccess())
                {
                    return false;
                }
            }
            $edFormats = UserEdFormatTable::query()->setSelect(['*'])->where('USER_ID', $this->entity->getID())->fetchCollection();
            foreach ($edFormats as $edFormat)
            {
                $result = UserEdFormatTable::delete([
                    'USER_ID' => $edFormat['USER_ID'],
                    'EDUCATION_FORMAT_ID' => $edFormat['EDUCATION_FORMAT_ID'],
                ]);
                if (!$result->isSuccess())
                {
                    return false;
                }
            }
            $result = UserDescriptionTable::delete($this->entity->getID());
            if (!$result->isSuccess())
            {
                return false;
            }

            $feedbacks = FeedbacksTable::query()->setSelect(['*'])->where('TUTOR_ID', $this->entity->getID())->fetchCollection();
            foreach ($feedbacks as $feedback)
            {
                $result = FeedbacksTable::delete([
                    'ID' => $feedback['ID'],
                ]);
                if (!$result->isSuccess())
                {
                    return false;
                }
            }
            $freeTime = FreeTimeTable::query()->setSelect(['*'])->where('USER_ID', $this->entity->getID())->fetchCollection();
            foreach ($freeTime as $hour)
            {
                $result = FreeTimeTable::delete([
                    'ID' => $hour['ID'],
                ]);
                if (!$result->isSuccess())
                {
                    return false;
                }
            }

            $images = ProfileImagesTable::query()
                ->setSelect(['ID'])
                ->where('USER_ID', $this->entity->getID())
                ->fetchCollection();

            foreach ($images as $image)
            {
                $result = ProfileImagesTable::delete([
                    'ID' => $image['ID'],
                ]);
                if (!$result->isSuccess())
                {
                    return false;
                }
            }

            $VKs = VkTable::query()
                ->setSelect(['*'])
                ->where('USER_ID', $this->entity->getID())
                ->fetchCollection();
            foreach ($VKs as $VK)
            {
                $result = VkTable::delete([
                    'ID' => $VK['ID'],
                ]);
                if (!$result->isSuccess())
                {
                    return false;
                }
            }
            $telegramUsernames = TelegramTable::query()
                ->setSelect(['*'])
                ->where('USER_ID', $this->entity->getID())
                ->fetchCollection();
            foreach ($telegramUsernames as $telegramUsername)
            {
                $result = TelegramTable::delete([
                    'ID' => $telegramUsername['ID'],
                ]);
                if (!$result->isSuccess())
                {
                    return false;
                }
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
        return true;
    }

    public function UpdatePassword(string $oldPassword, string $newPassword, string $passwordConfirm)
    {
        global $USER;
        return $USER->ChangePassword(
            $USER->GetLogin(), '',
            $newPassword, $passwordConfirm,
            false, '',
            0, true,
            '', $oldPassword
        );
    }

    public function saveProfilePhoto()
    {
        $service = (new ImagesService($this->entity->getID()));
        $file = $service->getLastTmpFile();
        if ($file === null)
        {
            (new ErrorService('file_not_found'))->getLastError();
        }
        $service->clearTrash($service->getAvatarDir());

        $name = preg_replace('#.+[\\\/]#', '', $file);
        $newPlace = $service->saveProfileImage($name);

        $service->clearTrash($service->getTmpDir());
        if (!$service->getErrors()->isNoErrors())
        {
            return $service->getErrors()->getLastError();
        }
        return ['TYPE' => 'OK', 'MESSAGE' => $service::cutPathToProjectRoot($newPlace)];
    }

    public function setBlockStatus(int $userID, string $blocked)
    {
        $user = new CUser();
        return $user->Update($userID, [
            'BLOCKED' => $blocked,
        ]);
    }

    public function isBlocked()
    {
        $result = UserTable::query()
            ->setSelect(['BLOCKED'])
            ->where('ID', $this->entity->getID())
            ->fetchObject();

        return $result['BLOCKED'];
    }

    public function deleteUserSubject(int $subjectID)
    {
            $result = UserSubjectTable::delete([
                'USER_ID' => $this->entity->getID(),
                'SUBJECT_ID' => $subjectID,
            ]);
            if (!$result->isSuccess())
            {
                return $result->getErrorMessages();
            }

        return true;
    }
}