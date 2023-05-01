<?php

namespace Up\Tutortoday\Model\FormObjects;

use Bitrix\Main\Type\ParameterDictionary;

class FeedbackForm
{
    private string $title;
    private string $description;
    private int $receiverID;
    private int $stars;
    public function __construct(ParameterDictionary $post)
    {
        $this->title = $post['title'];
        $this->description = $post['description'];
        $this->receiverID = (int)$post['receiverID'];
        $this->stars = (int)$post['stars'];
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getReceiverID(): int
    {
        return $this->receiverID;
    }

    public function getStars(): int
    {
        return $this->stars;
    }
}